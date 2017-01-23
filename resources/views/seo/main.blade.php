<div class="row">
    <div class="input-field col s6">
        <input type="text"
               name="seo_title"
               id="seo_title"
               value="{{ old('seo_title', isset($data->seo_title) ? $data->seo_title: '') }}">
        <label for=" seo_title">Seo title</label>
    </div>
    <div class="input-field col s6">
        <input type="text"
               name="seo_description"
               id="seo_description"
               value="{{ old('seo_description', isset($data->seo_description) ? $data->seo_description : '') }}">
        <label for="seo_description">Seo description</label>
    </div>
</div>
<div class="row">
    <div class="input-field col s6">
        <input type="text"
               name="seo_keywords"
               id="seo_keywords"
               value="{{ old('seo_keywords', isset($data->seo_keywords) ? $data->seo_keywords : '') }}">
        <label for="seo_keywords">Seo keywords</label>
    </div>
    <div class="input-field col s6">
        <input type="text"
               name="seo_path"
               id="seo_path"
               value="{{ old('seo_path', isset($item->seo_path) ? $item->seo_path: '') }}">
        <label for=" seo_path">Seo path</label>
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        <input type="hidden"
               name="seo_hidden"
               value="0">
        <input type="checkbox"
               name="seo_hidden"
               id="seo_hidden"
               value="1"
               {{ isset($data->seo_hidden) ? '' : 'selected' }}>
        <label for="seo_hidden">Seo hidden</label>
    </div>
</div>