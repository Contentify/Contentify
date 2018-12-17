<?php

namespace App\Modules\CashFlows\Http\Controllers;

use App\Modules\CashFlows\CashFlow;
use BackController;
use Config;
use Contentify\CsvWriter;
use Hover;
use HTML;
use ModelHandlerTrait;
use Response;
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

        $revenues = (int) CashFlow::sum('integer_revenues');
        $expenses = (int) CashFlow::sum('integer_expenses');
        $total = $revenues - $expenses;

        $this->indexPage([
            'buttons'   => ['new', HTML::button('CSV '.trans('app.export'), url('admin/cash-flows/export'))],
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
                $titleColumn = Hover::modelAttributes($cashFlow, ['creator', 'updated_at'])->pull().
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
            '<strong>'.($revenues / 100).'</strong> '.$currency.' '.trans('app.revenues').' - '
            .'<strong>'.($expenses / 100).'</strong> '.$currency.' '.trans('app.expenses').' = '
            .'<span class="'.$totalClass.'"<strong>'.($total / 100).'</strong> '.$currency.' '.trans('app.total').'</span>'
            .'</div>';
        $page->with('modelTable', $modelTable);
    }

    /**
     * Export all cash flows as a .CSV file
     */
    public function export()
    {
        $cashFlows = CashFlow::all();

        $csvWriter = new CsvWriter();

        $csvWriter->insertOne([
            trans('app.id'),
            trans('app.title'),
            trans('app.description'),
            trans('app.revenues'),
            trans('app.expenses'),
            trans('app.paid'),
            trans('app.date'),
            trans('app.person'),
        ]);

        foreach ($cashFlows as $cashFlow) {
            $record = [
                $cashFlow->id,
                $cashFlow->title,
                $cashFlow->description,
                $cashFlow->revenues,
                $cashFlow->expenses,
                $cashFlow->paid,
                $cashFlow->paid_at->dateTime(),
                $cashFlow->user_id,
            ];

            $csvWriter->insertOne($record);
        }

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="download.csv"',
        ];

        return Response::make($csvWriter->getContent(), 200, $headers);
    }

}
