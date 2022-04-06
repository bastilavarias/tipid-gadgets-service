<?php


namespace App\Helpers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;

class ExtendedResponse
{
    protected $data = [];

    protected $code = 200;

    protected $success = TRUE;

    protected $message = '';

    protected $slug = '';

    protected $pagination = [];


    public function __construct($data = NULL, $message = NULL)
    {

        if (is_null($data) === FALSE) {
            $this->data($data);
        }

        if (is_null($message) === FALSE) {
            $this->message($message);
        }
    }

    public function code(int $code): ExtendedResponse
    {
        $this->code = $code;

        return $this;
    }

    // generic success code
    public function success($code = 200): ExtendedResponse
    {
        $this->code = $code;
        $this->success = TRUE;

        return $this;
    }

    // generic failure code
    public function failed($code = 400): ExtendedResponse
    {
        $this->code = $code;
        $this->success = FALSE;

        return $this;
    }

    // lacks authentication method
    // if auth middleware is not activated by default
    public function unathorized(): ExtendedResponse
    {
        $this->code = 401;
        $this->success = FALSE;

        return $this;
    }

    // user permission specific errors
    public function forbidden(): ExtendedResponse
    {
        $this->code = 403;
        $this->success = FALSE;

        return $this;
    }

    // model search related errors
    public function notFound(): ExtendedResponse
    {
        $this->code = 404;
        $this->success = FALSE;

        return $this;
    }

    // set a custom slug
    public function slug(string $value): ExtendedResponse
    {
        $this->slug = $value;

        return $this;
    }

    public function message(string $value): ExtendedResponse
    {
        if ($this->slug == '') {
            // set slug too
            $this->slug = Str::slug($value, '_');
        }

        $this->message = $this->translateMessage($value);

        return $this;
    }

    // implement a message translator based on slug given
    protected function translateMessage($fallback)
    {

        return $fallback;
    }

    public function data($value): ExtendedResponse
    {

        if($value instanceof AnonymousResourceCollection){
            $value = $value->resource;
        }

        if ($value instanceof Paginator || $value instanceof LengthAwarePaginator) {
            // convert pagination to array
            $pagination = $value->toArray();
            $data = $pagination['data'];
            unset($pagination['data']);

            // separate them on two different array keys to create uniformity
            $this->pagination = $pagination;
            $this->data = $data;
        } else {
            $this->data = $value;
        }

        return $this;
    }

    public function generate()
    {
        return $this->generateResponse();
    }

    protected function generateResponse()
    {
        return response()->json([
            'success'     => $this->success,
            'code'        => $this->code,
            'slug'        => $this->slug,
            'message'     => $this->message,
            'data'        => $this->data,
            'pagination'  => $this->pagination,
        ], $this->code);
    }
}
