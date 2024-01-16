<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ trans('user.name') }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($user->name) ? $user->name : ''}}" required>
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
    <label for="telephone" class="control-label">{{ trans('user.telephone') }}</label>
    <input class="form-control" name="telephone" type="text" id="telephone" value="{{ isset($user->telephone) ? $user->telephone : ''}}" required>
    {!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ trans('user.status') }}</label>
    <select name="status" class="form-control" id="status" required>
    @foreach (json_decode('{"0":"Inativo","1":"Ativo"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ (isset($user->status) && $user->status == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('afiliado') ? 'has-error' : ''}}">
    <label for="afiliado" class="control-label">{{ trans('user.afiliado') }}</label>
    <select name="afiliado" class="form-control" id="afiliado" required>
    @foreach (json_decode('{"0":"Sim","1":"N\u00e3o"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ (isset($user->afiliado) && $user->afiliado == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! $errors->first('afiliado', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('pix') ? 'has-error' : ''}}">
    <label for="pix" class="control-label">{{ trans('user.pix') }}</label>
    <input class="form-control" name="pix" type="text" id="pix" value="{{ isset($user->pix) ? $user->pix : ''}}" required>
    {!! $errors->first('pix', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('cpf') ? 'has-error' : ''}}">
    <label for="cpf" class="control-label">{{ trans('user.cpf') }}</label>
    <input class="form-control" name="cpf" type="text" id="cpf" value="{{ isset($user->cpf) ? $user->cpf : ''}}" required>
    {!! $errors->first('cpf', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label">{{ trans('user.email') }}</label>
    <input class="form-control" name="email" type="email" id="email" value="{{ isset($user->email) ? $user->email : ''}}" required>
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
    <label for="password" class="control-label">{{ trans('user.password') }}</label>
    <input class="form-control" name="password" type="password" id="password" >
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
