<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label"><?= htmlLabel('name') ?></label>
    <input class="form-control" name="name" type="text" id="name"
           value="{{ isset($user->name) ? $user->name : old('name')}}">
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
    <label for="telephone" class="control-label"><?= htmlLabel('telephone') ?></label>
    <input class="form-control" name="telephone" type="text" id="telephone"
           value="{{ isset($user->telephone) ? $user->telephone : old('telephone')}}">
    {!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label"><?= htmlLabel('status') ?></label>
    <select name="status" class="form-control" id="status">
        @foreach (json_decode('{"0":"Inactive","1":"Active"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($user->status) && $user->status == $optionKey) ? 'selected' : ''}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('pix') ? 'has-error' : ''}}">
    <label for="pix" class="control-label"><?= htmlLabel('pix') ?></label>
    <input class="form-control" name="pix" type="text" id="pix"
           value="{{ isset($user->pix) ? $user->pix : old('pix')}}">
    {!! $errors->first('pix', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('cpf') ? 'has-error' : ''}}">
    <label for="cpf" class="control-label"><?= htmlLabel('cpf') ?></label>
    <input class="form-control" name="cpf" type="text" id="cpf"
           value="{{ isset($user->cpf) ? $user->cpf : old('cpf')}}">
    {!! $errors->first('cpf', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label"><?= htmlLabel('email') ?></label>
    <input class="form-control" name="email" type="email" id="email"
           value="{{ isset($user->email) ? $user->email : old('email')}}">
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
    <label for="password" class="control-label"><?= htmlLabel('password') ?></label>
    <input class="form-control" name="password" type="password" id="password">
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group">
    <input class="btn btn-primary text-capitalize" type="submit"
           value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
</div>
