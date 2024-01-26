<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label"><?= htmlLabel('name') ?></label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($pixaccount->name) ? $pixaccount->name : old('name')}}">
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('beneficiary_name') ? 'has-error' : ''}}">
    <label for="beneficiary_name" class="control-label"><?= htmlLabel('beneficiary_name') ?></label>
    <input class="form-control" name="beneficiary_name" type="text" id="beneficiary_name" value="{{ isset($pixaccount->beneficiary_name) ? $pixaccount->beneficiary_name : old('beneficiary_name')}}">
    {!! $errors->first('beneficiary_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('key_type') ? 'has-error' : ''}}">
    <label for="key_type" class="control-label"><?= htmlLabel('key_type') ?></label>
    <select name="key_type" class="form-control" id="key_type">
    @foreach (json_decode('{"email":"email","cpf":"cpf","phone":"phone","cnpj":"cnpj","random":"random"}', true) as $optionKey => $optionValue)


        <option value="{{ $optionKey }}" {{ (isset($pixaccount->key_type) && $pixaccount->key_type == $optionKey) ? 'selected' : old('key_type')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
    @endforeach
</select>
    {!! $errors->first('key_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('key_value') ? 'has-error' : ''}}">
    <label for="key_value" class="control-label"><?= htmlLabel('key_value') ?></label>
    <input class="form-control" name="key_value" type="text" id="key_value" value="{{ isset($pixaccount->key_value) ? $pixaccount->key_value : old('key_value')}}">
    {!! $errors->first('key_value', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary text-capitalize" type="submit" value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
</div>
