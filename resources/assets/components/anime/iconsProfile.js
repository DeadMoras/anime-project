import Vue from 'vue';

const iconsProfile = Vue.component('icons-profile', {
    props: ['name'],

    template: `
        <div>
            <a title="Смотрю">
                <i class="fa fa-eye"
                   aria-hidden="true"></i>
            </a>
            <a title="Буду смотреть">
                <i class="fa fa-calendar-check-o"
                   aria-hidden="true"></i>
            </a>
            <a title="Просмотрено">
                <i class="fa fa-eye-slash"
                   aria-hidden="true"></i>
            </a>
            <a title="Любимое">
                <i class="fa fa-heart"
                   aria-hidden="true"></i>
            </a>
        </div>
    `,
});

export default {
    iconsProfile
}