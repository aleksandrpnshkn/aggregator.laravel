import './bootstrap';
import Vue from 'vue';
import Buefy from "buefy";

import DrivingSchoolsFilter from './components/DrivingSchoolsFilter';
import CreateDrivingSchool from './components/CreateDrivingSchool';

Vue.use(Buefy, {
    defaultIconPack: 'fas',
});

new Vue({
    el: '#root',
    components: {
        DrivingSchoolsFilter,
        CreateDrivingSchool,
    }
});
