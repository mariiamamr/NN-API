<?php
namespace App\Container\Contracts\File;

use App\Container\Contracts\Files\FilesContract;
use App\Container\Contracts\Files\ImagesEloquent;
use App\Models\File;
use ImageLib;
use Storage;

class FilesEloquent implements FilesContract
{
    protected $pagination = 9;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function get($id)
    {
        return $this->file->findOrFail($id);
    }

    public function getAll()
    {
        return $this->file->all();
    }

    public function getPaginated()
    {
        return $this->file->paginate($this->pagination);
    }

    public function set($request, $watermark = null, $data = []) // date can't be null
    {
        // validate the watermark

        if ($request->hasFile('watermark') && $request->file('watermark')->isValid()) {
            $watermark = $request->file('watermark');
        }

        // validate the main image
        if ($request->hasFile('thumb') && $request->file('thumb')->isValid()) {
            $file = $request->file('thumb');
        }

        $title = md5(microtime()) . rand(0000, 9999);
        $ext = $file->extension();
        $path = 'images/' . date('Y') . '/' . date('m') . '/';

        $df = $path . $title . $ext;
        $lg = $path . $title . '_lg.' . $ext;
        $md = $path . $title . '_md.' . $ext;
        $sm = $path . $title . '_sm.' . $ext;
        $xs = $path . $title . '_xs.' . $ext;

        $df_file = ImageLib::make($file);
        $lg_file = ImageLib::make($file)->resize(720, 405);
        $md_file = ImageLib::make($file)->resize(600, 342);
        $sm_file = ImageLib::make($file)->resize(220, 124);
        $xs_file = ImageLib::make($file)->resize(40, 40);

        if (!is_null($watermark)) {
            $watermark = ImageLib::make($watermark)->resize(107, 60);
            $df_file->insert($watermark, 'center', 0, 0);
            $lg_file->insert($watermark, 'center', 0, 0);
            $md_file->insert($watermark, 'center', 0, 0);
            $sm_file->insert($watermark, 'center', 0, 0);
        }

        Storage::disk(env('STORAGE_DISK'))->put($lg, $df_file->stream()->detach(), 'public');
        Storage::disk(env('STORAGE_DISK'))->put($lg, $lg_file->stream()->detach(), 'public');
        Storage::disk(env('STORAGE_DISK'))->put($md, $md_file->stream()->detach(), 'public');
        Storage::disk(env('STORAGE_DISK'))->put($sm, $sm_file->stream()->detach(), 'public');
        Storage::disk(env('STORAGE_DISK'))->put($xs, $xs_file->stream()->detach(), 'public');

        $this->file->title = $path . $title;
        $this->file->ext = $ext;
        $this->file->size = $file->getClientSize();
        $this->file->type = 'image';
        $this->file->mime = $file->getMimeType();
        $this->file->original_title = $file->getClientOriginalName();

        if (count($data) > 0) {
            if (!empty($request->title))
                $this->file->original_title = $request->title;

            if (!empty($request->caption))
                $this->file->caption = $request->caption;
        }

        $this->file->save();

        if (isset($request->editor))
            return response()->json([
            'url' => thumbByUrl($path . $title . '_lg.' . $ext)
        ]);

        return $this->file->id;
    }

    public function setByUrl($url)
    {
        $title = md5(microtime()) . rand(0000, 9999);
        $file = file_get_contents($url);
        $extArray = explode('.', $url);
        $ext = end($extArray);
        $path = 'images/' . date('Y') . '/' . date('m') . '/';
        $relativePath = public_path('storage/' . $path);
        $lg = $title . '_lg.' . $ext;
        $xs = $title . '_xs.' . $ext;
        $default = $path . $title;
        $file_info = new \finfo(FILEINFO_MIME_TYPE);

        Storage::disk(env('STORAGE_DISK'))->put($path . $title . '.' . $ext, $file, 'public');
        Storage::disk(env('STORAGE_DISK'))->put($path . $lg, $file, 'public');
        Storage::disk(env('STORAGE_DISK'))->put($path . $xs, $file, 'public');

        $this->file->title = $default;
        $this->file->ext = $ext;
        $this->file->size = strlen($file);
        $this->file->type = 'image';
        $this->file->mime = $file_info->buffer($file);
        $this->file->original_title = $title;
        $this->file->save();

        return $this->file->id;
    }

    public function encodeImage($image)
    {
        $path = $image->getRealPath();
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $content = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($content);
    }

    public function uploadSingle($file)
    {
        $title = md5(microtime()) . rand(0000, 9999);
        $ext = $file->extension();
        $upload = Storage::disk(env('STORAGE_DISK'))->putFileAs('images/' . date('Y') . '/' . date('m'), $file, $title . '.' . $ext, 'public');

        if (!$upload)
            return null;

        return $upload;
    }

    public function update($file, $id)
    {
        $file = $this->get($id);

        $values = [
            'title' => $data->title
        ];

        $result = $file->update($values);

        return backendRedirect($result, 'categories', 'update');
    }

    public function delete($id)
    {
        return $this->get($id)->delete();
    }

    public function uploadCertificate($file)
    {
        $fileName="";
        if ($file && $file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            $fileNameWithoutExtension=time().rand(11111,99999);
            $filePath=$fileNameWithoutExtension.'.'.$extension;
            $fileName ='files/'.$filePath;
            $file->move(public_path().'/files/',$fileName);
        }
        return $fileName;
    }

    
    public function uploadTeacherProfile($image)
    {
        $fileName="";
        if ($image && $image->isValid()) {
            $extension = $image->getClientOriginalExtension();
            $fileNameWithoutExtension=time().rand(11111,99999);
            $filePath=$fileNameWithoutExtension.'.'.$extension;
            $fileName ='images/'.$filePath;
            $image->move(public_path().'/images/',$fileName);
        }
        return $fileName;
    }
}
