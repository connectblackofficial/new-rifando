<div class="form-group {{ $errors->has('nome') ? 'has-error' : ''}}">
    <label for="nome" class="control-label"><?= htmlLabel('nome') ?></label>
    <input class="form-control" name="nome" type="text" id="nome"
           value="{{ isset($customer->nome) ? $customer->nome : old('nome')}}">
    {!! $errors->first('nome', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
    <label for="telephone" class="control-label"><?= htmlLabel('telephone') ?></label>
    <input class="form-control" name="telephone" type="text" id="telephone"
           value="{{ isset($customer->telephone) ? $customer->telephone : old('telephone')}}">
    {!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('ddi') ? 'has-error' : ''}}">
    <label for="ddi" class="control-label"><?= htmlLabel('ddi') ?></label>
    <select name="ddi" class="form-control" id="ddi">
        @foreach (getCountriesDdi() as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($customer->ddi) && $customer->ddi == $optionKey) ? 'selected' : old('ddi')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('ddi', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label"><?= htmlLabel('email') ?></label>
    <input class="form-control" name="email" type="text" id="email"
           value="{{ isset($customer->email) ? $customer->email : old('email')}}">
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('cpf') ? 'has-error' : ''}}">
    <label for="cpf" class="control-label"><?= htmlLabel('cpf') ?></label>
    <input class="form-control" name="cpf" type="text" id="cpf"
           value="{{ isset($customer->cpf) ? $customer->cpf : old('cpf')}}">
    {!! $errors->first('cpf', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary text-capitalize" type="submit"
           value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
</div>
