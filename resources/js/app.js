import './bootstrap';
import Vue from 'vue';
import Buefy from "buefy";

import DrivingSchoolsFilter from './components/DrivingSchoolsFilter';

Vue.use(Buefy, {
    defaultIconPack: 'fas',
});

new Vue({
    el: '#root',
    components: {
        DrivingSchoolsFilter,
    }
});
