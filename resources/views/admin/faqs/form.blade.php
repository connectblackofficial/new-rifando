<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label"><?= htmlLabel('title') ?></label>
    <input class="form-control" name="title" type="text" id="title"
           value="{{ isset($faq->title) ? $faq->title : old('title')}}">
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label"><?= htmlLabel('description') ?></label>
    <textarea class="form-control text-area-ckeditor" rows="5" name="description" type="textarea"
              id="description">{{ isset($faq->description) ? $faq->description : old('description')}}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('order') ? 'has-error' : ''}}">
    <label for="order" class="control-label"><?= htmlLabel('order') ?></label>
    <input class="form-control" name="order" type="number" id="order"
           value="{{ isset($faq->order) ? $faq->order : old('order')}}">
    {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('show') ? 'has-error' : ''}}">
    <label for="show" class="control-label">Exibir na página principal</label>
    <select name="show" class="form-control" id="show">
        @foreach (\App\Enums\YesNoAsIntEnum::getValueAsSelectedNew() as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($faq->show) && $faq->show == $optionKey) ? 'selected' : old('show')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('show', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary text-capitalize" type="submit"
           value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
</div>
@section("scripts-footer")
    @include("crud.cke-editor")

@endsection