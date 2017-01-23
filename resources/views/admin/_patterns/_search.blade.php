<div class="search"
     id="searchVue">
    <div class="input-field">
        <input type="text"
               id="search_input"
               v-model="searchInput"
               @keyup.enter="justSearch">
        <label for="search_input">Search</label>
    </div>
</div>