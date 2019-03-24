@extends('joystick-admin.layout')

@section('content')
  <h2 class="page-header">Галерея</h2>

  @include('joystick-admin.partials.alerts')

  <p class="text-right">
    <a href="/admin/gallery/create" class="btn btn-success btn-sm">Добавить</a>
  </p>

  <div class="row">
    @foreach($gallery as $image)
      <div class="col-md-2" style="margin-bottom: 30px;">
        <a href="/img/gallery/{{ $image->image }}"><img src="/img/gallery/mini-{{ $image->image }}" class="img-responsive"></a>
        <h5>{{ $image->title }}</h5>
        <a class="btn btn-link btn-xs" href="{{ route('gallery.edit', $image->id) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
        <form class="btn-delete" method="POST" action="{{ route('gallery.destroy', $image->id) }}" accept-charset="UTF-8">
          <input name="_method" type="hidden" value="DELETE">
          <input name="_token" type="hidden" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
        </form>
      </div>
    @endforeach
  </div>
@endsection
