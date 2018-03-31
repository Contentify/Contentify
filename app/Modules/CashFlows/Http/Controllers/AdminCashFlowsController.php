<?php

namespace App\Modules\CashFlows\Http\Controllers;

use App\Modules\CashFlows\CashFlow;
use BackController;
use Config;
use Hover;
use HTML;
use ModelHandlerTrait;
use URL;

class AdminCashFlowsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'chart-line';

    public function __construct()
    {
        $this->modelClass = CashFlow::class;

        parent::__construct();
    }

    public function index()
    {
        $currency = ' ('.Config::get('app.currency_symbol').')';

        $revenues = (int) CashFlow::sum('revenues');
        $expenses = (int) CashFlow::sum('expenses');
        $total = $revenues - $expenses;

        $this->indexPage([
            'tableHead' => [
                trans('app.id')                 => 'id',
                trans('app.paid')               => 'paid',
                trans('app.title')              => 'title',
                trans('app.revenues').$currency => 'revenue',
                trans('app.expenses').$currency => 'expense',
                trans('app.person')             => 'user_id',
                trans('app.date')               => 'paid_at'
            ],
            'tableRow' => function(CashFlow $cashFlow)
            {
                $titleColumn = Hover::modelAttributes($cashFlow, ['creator'])->pull().
                    $cashFlow->title.
                    '<div class="info-text">'.$cashFlow->description.'</div>';

                $user = $cashFlow->user ?
                    raw(HTML::link(URL::route('users.show', [$cashFlow->user->id]), $cashFlow->user->username)) :
                    '';

                return [
                    $cashFlow->id,
                    raw($cashFlow->paid ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw($titleColumn),
                    $cashFlow->revenues > 0 ? raw('<span class="revenues">'.e($cashFlow->revenues).'</span>') : '',
                    $cashFlow->expenses > 0 ? raw('<span class="expenses">'.e($cashFlow->expenses).'</span>') : '',
                    $user,
                    $cashFlow->paid_at,
                ];            
            }
        ]);

        /** @var \Illuminate\View\View $this->layout */
        $layoutData = $this->layout->getData();
        /** @var \Illuminate\View\View $page */
        $page = $layoutData['page'];
        $modelTable = $page->getData()['modelTable'];

        $currency = Config::get('app.currency');
        $totalClass = $revenues >= $expenses ? 'revenues' : 'expenses';
        $modelTable.=
            '<div class="summary">'.
            '<span class="revenues"><strong>'.$revenues.'</strong> '.$currency.' '.trans('app.revenues').'</span> - '
            .'<span class="expenses"><strong>'.$expenses.'</strong> '.$currency.' '.trans('app.expenses').'</span> = '
            .'<span class="'.$totalClass.'"<strong>'.$total.'</strong> '.$currency.' '.trans('app.total').'</span>'
            .'</div>';
        $page->with('modelTable', $modelTable);
    }

}