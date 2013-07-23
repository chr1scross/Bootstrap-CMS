<?php

class PageController extends BaseController {

    protected $page;

    /**
     * Constructor
     */
    public function __construct(Page $page) {
        $this->page = $page;
        $this->edits[] = 'create';
        $this->edits[] = 'store';
        $this->edits[] = 'edit';
        $this->edits[] = 'update';
        $this->edits[] = 'destroy';
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        Session::flash('', ''); // work around laravel bug
        Session::reflash();
        Log::info('Redirecting from pages to the home page');
        return Redirect::route('pages.show', array('pages' => 'home'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $this->page = new Page;

        $input = array(
            'title' => Binput::get('title'),
            'slug' => urlencode(strtolower(str_replace(' ', '-', Binput::get('title')))),
            'title' => Binput::get('title'),
            'body' => Input::get('body'), // use standard Input
            'show_title' => (Binput::get('show_title') == 'on'),
            'show_nav' => (Binput::get('show_nav') == 'on'),
            'icon' => Binput::get('icon'),
            'author_id' => Sentry::getUser()->getId());

        $this->page->fill($input);
        if ($this->page->save()) {
            Session::flash('success', 'Your page has been created successfully.');
            return Redirect::route('base');
        } else {
            return Redirect::route('pages.create')->withInput()->withErrors($this->page->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return Response
     */
    public function show($slug) {
        $page = null;
        try {
            $page = Page::where('slug', '=', $slug)->firstOrFail();
        } catch (Exception $e) {
            App::abort(404, 'Page Not Found');
        }
        if (!$page) {
            App::abort(404, 'Page Not Found');
        }
        return View::make('pages.show', array('page' => $page));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return Response
     */
    public function edit($slug) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $slug
     * @return Response
     */
    public function update($slug) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return Response
     */
    public function destroy($slug) {
        //
    }
}