<?php

class Comments {

    public static function show($foreignType, $foreignId)
    {
        $comments = Comment::where('foreign_type', '=', $foreignType)->where('foreign_id', '=', $foreignId)->get();

        echo(View::make('comments.show', compact('comments', 'foreignType', 'foreignId'))->render());

        if (user()) {
            echo(View::make('comments.form', compact('foreignType', 'foreignId'))->render());
        }
    }

    /**
     * Creates (stores) a comment
     * 
     * @param  string   $foreignType The foreign type identifier
     * @param  int      $foreignId   The foreign id (can be 0)
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function store($foreignType, $foreignId)
    {
        $comment = new Comment(Input::all());
        $comment->foreign_type  = $foreignType;
        $comment->foreign_id    = $foreignId;
        $comment->creator_id    = user()->id;
        $comment->updater_id    = user()->id;

        $okay = $comment->save();

        if (! $okay) {

        }

        return Redirect::to(Input::get('_url'));
    }

    /**
     * 
     * @param  int $id The ID of the comment
     * @return mixed
     */
    public static function get($id)
    {
        $comment = Comment::findOrFail($id);

        return Response::json($comment);
    }

    /**
     * Edits a comment
     * @param  int $id The ID of the comment
     * @return void
     */
    public static function edit($id)
    {
        $comment = Comment::findOrFail($id);

        echo(View::make('comments.form', compact('comment'))->render());
    }

    /**
     * Updates a comment
     * @param  int $id The ID of the comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function update($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->fill(Input::all());
        $comment->updater_id = user()->id;

        $okay = $comment->save();

        if (! $okay) {

        }

        return Redirect::to(URL::previous());
    }

    /**
     * Deletes a comment
     * @param  int $id The ID of the comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function delete($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return Response::make('', 200);
    }
}