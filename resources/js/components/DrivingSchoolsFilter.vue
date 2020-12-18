<template>
    <div class="driving-schools-filter">
        <form class="" method="get" @submit="onSubmit">
            <b-field label="Регион">
                <b-select v-model="region"
                          name="region"
                          aria-label="Регион"
                          :loading="isLoadingRegions"
                          @change.native="onChangeRegion"
                          expanded
                >
                    <option v-for="region in regions" :value="region">{{ region }}</option>
                </b-select>
            </b-field>

            <b-field label="Город/Населенный пункт">
                <b-select v-model="city"
                          aria-label="Город"
                          :loading="isLoadingCities"
                          :disabled="!region || !cities.length"
                          @change.native="onChangeCity"
                          expanded
                >
                    <option v-for="city in cities" :value="city">{{ city }}</option>
                </b-select>
            </b-field>

            <b-field label="Район">
                <b-select v-model="district"
                          aria-label="Район"
                          :loading="isLoadingDistricts"
                          :disabled="!region || !city || !districts.length"
                          expanded
                >
                    <option v-for="district in districts" :value="district">{{ district }}</option>
                </b-select>
            </b-field>

            <driving-categories-dropdown v-model="selectedDrivingCategories" :driving-categories="drivingCategories"></driving-categories-dropdown>

            <b-field label="Прочее">
                <b-switch v-model="isRetraining">Переподготовка</b-switch>
            </b-field>

            <b-field>
                <b-switch v-model="isAkpp">АКПП</b-switch>
            </b-field>

            <b-field>
                <b-switch v-model="hasConclusions">Есть действительные заключения</b-switch>
            </b-field>

            <button class="filter__submit button is-primary is-fullwidth">Найти</button>
        </form>

        <div class="driving-schools-filter__results">
            <h1 class="driving-schools-filter__results-title is-size-2">Автошколы</h1>

            <p class="driving-schools-filter__not-found" v-if="! drivingSchools.length">
                Не найдено
            </p>

            <driving-school-preview v-for="drivingSchool in drivingSchools"
                                    :key="drivingSchool.id"
                                    :drivingSchool="drivingSchool"
            ></driving-school-preview>

            <hr>
            <b-pagination
                :total="pagination.total"
                v-model="currentPage"
                order="is-centered"
                rounded
                :per-page="pagination.perPage"
                icon-pack="fas"
                icon-prev="angle-left"
                icon-next="angle-right"
                aria-next-label="Следующая страница"
                aria-previous-label="Предыдущая страница"
                aria-page-label="Страница"
                aria-current-label="Текущая страница">
            </b-pagination>
        </div>

        <b-loading v-model="isLoadingDrivingSchools"></b-loading>
    </div>
</template>

<script>
import DrivingCategoriesDropdown from "./DrivingCategoriesDropdown";
import DrivingSchoolPreview from './DrivingSchoolPreview';

export default {
    name: 'DrivingSchoolsFilter',

    components: {DrivingCategoriesDropdown, DrivingSchoolPreview},

    data() {
        return {
            region: null,
            regions: [''],
            isLoadingRegions: false,
            city: '',
            cities: [],
            isLoadingCities: false,
            district: '',
            districts: [],
            isLoadingDistricts: false,

            hasConclusions: false,
            isAkpp: false,
            isRetraining: false,

            drivingCategories: [],
            selectedDrivingCategories: [],

            drivingSchools: [],
            isLoadingDrivingSchools: false,

            currentPage: null,
            pagination: {
                firstPageUrl: '',
                from: null,
                lastPage: null,
                lastPageUrl: '',
                nextPageUrl: '',
                path: '',
                perPage: null,
                prevPageUrl: null,
                to: null,
                total: null,
            },
        };
    },

    computed: {
        /**
         * При каждом изменении удобнее заного собирать параметры, чтобы не возиться с вложенностью
         */
        params() {
            let params = new URLSearchParams();

            if (this.region) {
                params.append('region', this.region);

                if (this.city) {
                    params.append('city', this.city);

                    if (this.district) {
                        params.append('district', this.district);
                    }
                }
            }

            if (this.currentPage) {
                params.append('page', this.currentPage);
            }

            if (this.selectedDrivingCategories.length) {
                params.append('driving-categories', this.selectedDrivingCategories.join());
            }

            if (this.hasConclusions) {
                params.append('has-conclusions', this.hasConclusions);
            }

            if (this.isAkpp) {
                params.append('is-akpp', this.isAkpp);
            }

            if (this.isRetraining) {
                params.append('is-retraining', this.isRetraining);
            }

            return params;
        },
    },

    watch: {
        params() {
            history.pushState(null, '', '?' + this.params.toString());
        },

        currentPage() {
            this.filterDrivingSchools();
        },
    },

    async beforeCreate() {
        const res = await axios.get('/api/user');
        console.log(res);
    },

    created() {
        /*
         * Показывать нужные автошколы при прямом переходе по ссылке.
         * Нужно распарсить url и присвоить всё в data.
         */
        if (location.search) {
            // Возможно стоит заменить на https://github.com/medialize/URI.js ради IE?
            const params = new URLSearchParams(location.search);

            // Параметры адреса
            if (params.has('region')) {
                this.region = params.get('region');

                if (params.has('city')) {
                    this.city = params.get('city');

                    if (params.has('district')) {
                        this.district = params.get('district');
                    }
                }
            }

            // Параметры пагинации
            if (params.has('page')) {
                this.currentPage = Number(params.get('page'));
            }

            if (params.has('driving-categories')) {
                this.selectedDrivingCategories = params.get('driving-categories').split(',').map(Number);
            }

            if (params.has('has-conclusions')) {
                this.hasConclusions = true;
            }

            if (params.has('is-akpp')) {
                this.isAkpp = true;
            }

            if (params.has('is-retraining')) {
                this.isRetraining = true;
            }
        }

        this.filterDrivingSchools();

        // Без await чтобы сразу выполнялись следующие запросы
        this.loadRegions();
        this.loadCities();
        this.loadDistricts();

        this.loadDrivingCategories();
    },

    methods: {
        onSubmit(event) {
            event.preventDefault();
            this.filterDrivingSchools();
        },

        showLoadingError() {
            this.$buefy.toast.open({
                duration: 10000,
                message: 'Что-то пошло не так. Попробуйте повторить запрос или обновить страницу.',
                position: 'is-top',
                type: 'is-danger',
            });
        },

        async filterDrivingSchools() {
            this.isLoadingDrivingSchools = true;

            try {
                const responseData = (await axios.get('/api/driving-schools' + location.search)).data;
                this.drivingSchools = responseData.data;

                this.pagination = {
                    currentPage: responseData.current_page,
                    firstPageUrl: responseData.first_page_url,
                    from: responseData.from,
                    lastPage: responseData.last_page,
                    lastPageUrl: responseData.last_page_url,
                    nextPageUrl: responseData.next_page_url,
                    path: responseData.path,
                    perPage: responseData.per_page,
                    prevPageUrl: responseData.prev_page_url,
                    to: responseData.to,
                    total: responseData.total,
                };
            } catch (error) {
                this.showLoadingError();
            }

            this.isLoadingDrivingSchools = false;
        },

        onChangeRegion() {
            this.city = ''; // Обнулить данные в состоянии
            this.district = '';
            this.loadCities();
        },

        onChangeCity() {
            this.district = '';
            this.loadDistricts();
        },

        async loadRegions() {
            this.isLoadingRegions = true;
            try {
                this.regions = (await axios.get('/api/regions')).data;
                this.regions.unshift(''); // Добавить пустое значение для сброса
            } catch (error) {
                this.showLoadingError();
            }
            this.isLoadingRegions = false;
        },

        async loadCities() {
            this.isLoadingCities = true;
            let cities = [];

            const regionPart = encodeURIComponent(this.region);

            if (this.region) {
                try {
                    cities = (await axios.get(`/api/regions/${regionPart}/cities`)).data;
                } catch (error) {
                    this.showLoadingError();
                }
            }

            this.cities = cities;
            this.isLoadingCities = false;
        },

        async loadDistricts() {
            this.isLoadingDistricts = true;
            let disticts = []

            // Может попасться какой-нибудь слеш или другой недопустимый символ
            const regionPart = encodeURIComponent(this.region);
            const cityPart = encodeURIComponent(this.city);

            if (this.region && this.city) {
                try {
                    disticts = (await axios.get(`/api/regions/${regionPart}/cities/${cityPart}/districts`)).data;
                } catch (error) {
                    this.showLoadingError();
                }
            }

            this.districts = disticts;
            this.isLoadingDistricts = false;
        },

        async loadDrivingCategories() {
            try {
                this.drivingCategories = (await axios.get('/api/driving-categories')).data;
            } catch (error) {
                this.showLoadingError();
            }
        }
    },
};
</script>

<style scoped lang="scss">
@import "./../../sass/mixins";
@import "./../../sass/variables";

.driving-schools-filter {
    display: grid;
    grid-gap: 40px;

    @include media-breakpoint-up(md) {
        grid-gap: 20px;
        grid-template-columns: 1fr 2fr;
    }

    @include media-breakpoint-up(lg) {
        grid-gap: 40px;
        grid-template-columns: 1fr 3fr;
    }

    > form {
        @include media-breakpoint-up(md) {
            margin-top: 48px;
        }

        @include media-breakpoint-up(lg) {
            margin-top: 58px;
        }
    }

    .driving-school-preview {
        margin-bottom: 20px;

        @include media-breakpoint-up(md) {
            margin-bottom: 30px;
        }
    }
}

.driving-schools-filter__results-title {
    margin-bottom: 20px;

    @include media-breakpoint-up(lg) {
        margin-bottom: 30px;
    }
}

.driving-schools-filter__not-found {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 140px;

    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    color: $grey-light;

    @include media-breakpoint-up(md) {
        min-height: 200px;
        font-size: 30px;
    }

    @include media-breakpoint-up(lg) {
        min-height: 300px;
        font-size: 40px;
    }
}
</style>
