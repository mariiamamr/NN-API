<?php
namespace Repos\Newsletters;

use Contracts\Newsletters\NewslettersContract;
use App\Newsletter;

class NewslettersRepo  implements NewslettersContract
{
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
        $this->number_of_pages = config('static.pagination.rowsPerPage');
    }

    public function get($id)
    {
        return $this->newsletter->findOrFail($id);
    }

    public function getAll()
    {
        return $this->newsletter->all();
    }

    public function paginate()
    {
        return $this->newsletter->paginate();
    }

    public function set($data)
    {
        return $this->newsletter->create([
            "email" => $data->email
        ]);
    }
}
