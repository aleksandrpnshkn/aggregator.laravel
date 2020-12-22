import './bootstrap';
import Vue from 'vue';
import Buefy from "buefy";

import DrivingSchoolsFilter from './components/DrivingSchoolsFilter';
import CreateDrivingSchool from './components/CreateDrivingSchool';
import EditDrivingSchool from './components/EditDrivingSchool';
import LearningPlacesTable from './components/LearningPlacesTable';
import EditLearningPlace from './components/EditLearningPlace';
import ProgramsTable from './components/ProgramsTable';
import EditProgram from './components/EditProgram';

Vue.use(Buefy, {
    defaultIconPack: 'fas',
});

new Vue({
    el: '#root',
    components: {
        DrivingSchoolsFilter,
        CreateDrivingSchool,
        EditDrivingSchool,
        LearningPlacesTable,
        EditLearningPlace,
        ProgramsTable,
        EditProgram,
    }
});
