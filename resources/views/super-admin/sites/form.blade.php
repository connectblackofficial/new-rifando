<div class="form-group {{ $errors->has('subdomain') ? 'has-error' : ''}}">
    <label for="subdomain" class="control-label"><?= htmlLabel('subdomain') ?></label>
    <input class="form-control" name="subdomain" type="text" id="subdomain"
           value="{{ isset($site->subdomain) ? $site->subdomain : old('subdomain')}}">
    {!! $errors->first('subdomain', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label"><?= htmlLabel('name') ?></label>
    <input class="form-control" name="name" type="text" id="name"
           value="{{ isset($site->name) ? $site->name : old('name')}}">
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('token_api_wpp') ? 'has-error' : ''}}">
    <label for="token_api_wpp" class="control-label"><?= htmlLabel('token_api_wpp') ?></label>
    <input class="form-control" name="token_api_wpp" type="text" id="token_api_wpp"
           value="{{ isset($site->token_api_wpp) ? $site->token_api_wpp : old('token_api_wpp')}}">
    {!! $errors->first('token_api_wpp', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('key_pix') ? 'has-error' : ''}}">
    <label for="key_pix" class="control-label"><?= htmlLabel('key_pix') ?></label>
    <input class="form-control" name="key_pix" type="text" id="key_pix"
           value="{{ isset($site->key_pix) ? $site->key_pix : old('key_pix')}}">
    {!! $errors->first('key_pix', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('token_asaas') ? 'has-error' : ''}}">
    <label for="token_asaas" class="control-label"><?= htmlLabel('token_asaas') ?></label>
    <input class="form-control" name="token_asaas" type="text" id="token_asaas"
           value="{{ isset($site->token_asaas) ? $site->token_asaas : old('token_asaas')}}">
    {!! $errors->first('token_asaas', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('pixel') ? 'has-error' : ''}}">
    <label for="pixel" class="control-label"><?= htmlLabel('pixel') ?></label>
    <textarea class="form-control" rows="5" name="pixel" type="textarea"
              id="pixel">{{ isset($site->pixel) ? $site->pixel : old('pixel')}}</textarea>
    {!! $errors->first('pixel', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('logo') ? 'has-error' : ''}}">
    <label for="logo" class="control-label"><?= htmlLabel('logo') ?></label>
    <input class="form-control" name="logo" type="text" id="logo"
           value="{{ isset($site->logo) ? $site->logo : old('logo')}}">
    {!! $errors->first('logo', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('footer') ? 'has-error' : ''}}">
    <label for="footer" class="control-label"><?= htmlLabel('footer') ?></label>
    <textarea class="form-control" rows="5" name="footer" type="textarea"
              id="footer">{{ isset($site->footer) ? $site->footer : old('footer')}}</textarea>
    {!! $errors->first('footer', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('active') ? 'has-error' : ''}}">
    <label for="active" class="control-label"><?= htmlLabel('active') ?></label>
    <select name="active" class="form-control" id="active">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->active) && $site->active == $optionKey) ? 'selected' : old('active')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('regulation') ? 'has-error' : ''}}">
    <label for="regulation" class="control-label"><?= htmlLabel('regulation') ?></label>
    <textarea class="form-control" rows="5" name="regulation" type="textarea"
              id="regulation">{{ isset($site->regulation) ? $site->regulation : old('regulation')}}</textarea>
    {!! $errors->first('regulation', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('user_term') ? 'has-error' : ''}}">
    <label for="user_term" class="control-label"><?= htmlLabel('user_term') ?></label>
    <textarea class="form-control" rows="5" name="user_term" type="textarea"
              id="user_term">{{ isset($site->user_term) ? $site->user_term : old('user_term')}}</textarea>
    {!! $errors->first('user_term', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('policy_privay') ? 'has-error' : ''}}">
    <label for="policy_privay" class="control-label"><?= htmlLabel('policy_privay') ?></label>
    <textarea class="form-control" rows="5" name="policy_privay" type="textarea"
              id="policy_privay">{{ isset($site->policy_privay) ? $site->policy_privay : old('policy_privay')}}</textarea>
    {!! $errors->first('policy_privay', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('scripts_footer') ? 'has-error' : ''}}">
    <label for="scripts_footer" class="control-label"><?= htmlLabel('scripts_footer') ?></label>
    <textarea class="form-control" rows="5" name="scripts_footer" type="textarea"
              id="scripts_footer">{{ isset($site->scripts_footer) ? $site->scripts_footer : old('scripts_footer')}}</textarea>
    {!! $errors->first('scripts_footer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('scripts_top') ? 'has-error' : ''}}">
    <label for="scripts_top" class="control-label"><?= htmlLabel('scripts_top') ?></label>
    <textarea class="form-control" rows="5" name="scripts_top" type="textarea"
              id="scripts_top">{{ isset($site->scripts_top) ? $site->scripts_top : old('scripts_top')}}</textarea>
    {!! $errors->first('scripts_top', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('hide_winners') ? 'has-error' : ''}}">
    <label for="hide_winners" class="control-label"><?= htmlLabel('hide_winners') ?></label>
    <select name="hide_winners" class="form-control" id="hide_winners">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->hide_winners) && $site->hide_winners == $optionKey) ? 'selected' : old('hide_winners')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('hide_winners', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('enable_affiliates') ? 'has-error' : ''}}">
    <label for="enable_affiliates" class="control-label"><?= htmlLabel('enable_affiliates') ?></label>
    <select name="enable_affiliates" class="form-control" id="enable_affiliates">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->enable_affiliates) && $site->enable_affiliates == $optionKey) ? 'selected' : old('enable_affiliates')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('enable_affiliates', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('cpf_required') ? 'has-error' : ''}}">
    <label for="cpf_required" class="control-label"><?= htmlLabel('cpf_required') ?></label>
    <select name="cpf_required" class="form-control" id="cpf_required">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->cpf_required) && $site->cpf_required == $optionKey) ? 'selected' : old('cpf_required')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('cpf_required', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email_required') ? 'has-error' : ''}}">
    <label for="email_required" class="control-label"><?= htmlLabel('email_required') ?></label>
    <select name="email_required" class="form-control" id="email_required">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->email_required) && $site->email_required == $optionKey) ? 'selected' : old('email_required')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('email_required', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('show_faqs') ? 'has-error' : ''}}">
    <label for="show_faqs" class="control-label"><?= htmlLabel('show_faqs') ?></label>
    <select name="show_faqs" class="form-control" id="show_faqs">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->show_faqs) && $site->show_faqs == $optionKey) ? 'selected' : old('show_faqs')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('show_faqs', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label"><?= htmlLabel('email') ?></label>
    <input class="form-control" name="email" type="text" id="email"
           value="{{ isset($site->email) ? $site->email : old('email')}}">
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('whatsapp') ? 'has-error' : ''}}">
    <label for="whatsapp" class="control-label"><?= htmlLabel('whatsapp') ?></label>
    <input class="form-control" name="whatsapp" type="text" id="whatsapp"
           value="{{ isset($site->whatsapp) ? $site->whatsapp : old('whatsapp')}}">
    {!! $errors->first('whatsapp', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label"><?= htmlLabel('description') ?></label>
    <input class="form-control" name="description" type="text" id="description"
           value="{{ isset($site->description) ? $site->description : old('description')}}">
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('og_image') ? 'has-error' : ''}}">
    <label for="og_image" class="control-label"><?= htmlLabel('og_image') ?></label>
    <input class="form-control" name="og_image" type="text" id="og_image"
           value="{{ isset($site->og_image) ? $site->og_image : old('og_image')}}">
    {!! $errors->first('og_image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('banner') ? 'has-error' : ''}}">
    <label for="banner" class="control-label"><?= htmlLabel('banner') ?></label>
    <input class="form-control" name="banner" type="text" id="banner"
           value="{{ isset($site->banner) ? $site->banner : old('banner')}}">
    {!! $errors->first('banner', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('require_user_terms_acept') ? 'has-error' : ''}}">
    <label for="require_user_terms_acept" class="control-label"><?= htmlLabel('require_user_terms_acept') ?></label>
    <select name="require_user_terms_acept" class="form-control" id="require_user_terms_acept">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ (isset($site->require_user_terms_acept) && $site->require_user_terms_acept == $optionKey) ? 'selected' : old('require_user_terms_acept')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('require_user_terms_acept', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('show_purchase_notifications') ? 'has-error' : ''}}">
    <label for="show_purchase_notifications"
           class="control-label"><?= htmlLabel('show_purchase_notifications') ?></label>
    <select name="show_purchase_notifications" class="form-control" id="show_purchase_notifications">
        @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)

            <option value="{{ $optionKey }}" {{ (isset($site->show_purchase_notifications) && $site->show_purchase_notifications == $optionKey) ? 'selected' : old('show_purchase_notifications')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('show_purchase_notifications', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group">
    <input class="btn btn-primary text-capitalize" type="submit"
           value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
</div>
