<?php

namespace App\Http\Controllers\Joystick;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\ImageTrait;
use App\Gallery;

use Storage;

class GalleryController extends Controller
{
    use ImageTrait;

    public function index()
    {
        $gallery = Gallery::orderBy('created_at')->get();

        return view('joystick-admin.gallery.index', ['gallery' => $gallery]);
    }

    public function create()
    {
        return view('joystick-admin.gallery.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png,svg,svgs,bmp,gif',
        ]);

        if ($request->hasFile('image')) {

            $imageName = time().'.'.$request->file('image')->getClientOriginalExtension();

	        // Creating mini image
	        $this->resizeMiniImage($request->file('image'), 380, 280, '/images/gallery/mini-'.$imageName, 100);

	        // Creating image
	        $this->resizeImage($request->file('image'), 1280, 768, '/images/gallery/'.$imageName, 100, null, '#000000');
        }

        $gallery = new Gallery;
        $gallery->sort_id = ($request->sort_id > 0) ? $request->sort_id : $gallery->count() + 1;
        $gallery->slug = str_slug($request->title);
        $gallery->title = $request->title;
        $gallery->image = $imageName;
        $gallery->path = '/images/gallery';
        $gallery->lang = 'ru';
        $gallery->status = ($request->status == 'on') ? 1 : 0;
        $gallery->save();

        return redirect('admin/gallery')->with('status', 'Запись добавлена!');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('joystick-admin.gallery.edit', ['gallery' => $gallery]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png,svg,svgs,bmp,gif',
        ]);

        $gallery = Gallery::findOrFail($id);

        if ($request->hasFile('image')) {

            $imageName = time().'.'.$request->file('image')->getClientOriginalExtension();

            // Creating mini image
            $this->resizeMiniImage($request->file('image'), 380, 280, '/images/gallery/mini-'.$imageName, 100);

            // Creating image
            $this->resizeImage($request->file('image'), 1280, 768, '/images/gallery/'.$imageName, 100, null, '#000000');

            Storage::delete([
                '/images/gallery/mini-'.$gallery->image,
                '/images/gallery/'.$gallery->image,
            ]);
        }

        $gallery->sort_id = ($request->sort_id > 0) ? $request->sort_id : $gallery->count() + 1;
        $gallery->slug = str_slug($request->title);
        $gallery->title = $request->title;
        if (isset($imageName)) $gallery->image = $imageName;
        $gallery->status = ($request->status == 'on') ? 1 : 0;
        $gallery->save();

        return redirect('admin/gallery')->with('status', 'Запись обновлена!');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if (!empty($gallery->image)) {

            Storage::delete([
                '/images/gallery/mini-'.$gallery->image,
                '/images/gallery/'.$gallery->image,
            ]);
        }

        $gallery->delete();

        return redirect('/admin/gallery');
    }

}