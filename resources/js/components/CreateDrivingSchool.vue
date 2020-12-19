<template>
    <form class="my-4" @submit.prevent="onSubmit">
        <b-steps v-model="activeStep" :has-navigation="false">
            <b-step-item label="Найти организацию">
                <h2 class="title has-text-centered my-6">Найти организацию</h2>

                <div v-if="suggestInn" class="field">
                    <label class="control">
                        <span class="label">Название, ИНН или ОГРН организации</span>
                        <input v-model="inn" ref="innField" class="input" type="text">
                    </label>
                    <p class="help">Введите данные и выберете подходящий вариант из подсказок. Если подсказки не работают, пропустите этот этап.</p>
                </div>

                <div class="buttons">
                    <button class="button" type="button" @click="skipSuggestion">Продолжить</button>
                </div>
            </b-step-item>

            <b-step-item label="Ввести данные">
                <h2 class="title has-text-centered my-5">Добавить автошколу</h2>

                <div class="columns">
                    <div class="column">
                        <b-field label="Название"
                                 :type="nameMessage ? 'is-danger' : null"
                                 :message="nameMessage"
                        >
                            <b-input v-model="name" :disabled="!!slug" @input="nameMessage = ''"></b-input>
                        </b-field>
                    </div>
                    <div class="column">
                        <b-field label="Полное наименование организации"
                                 :type="legal_nameMessage ? 'is-danger' : null"
                                 :message="legal_nameMessage"
                                 custom-class="required"
                        >
                            <b-input v-model="legal_name" required :disabled="!!slug" @input="legal_nameMessage = ''"></b-input>
                        </b-field>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <b-field label="ИНН"
                                 :type="innMessage ? 'is-danger' : null"
                                 :message="innMessage"
                                 custom-class="required"
                        >
                            <b-input v-model="inn" type="number" required :disabled="!!slug" @input="innMessage = ''"></b-input>
                        </b-field>
                    </div>
                    <div class="column">
                        <address-field v-model="address"
                                       :address-message="addressMessage"
                                       :disabled="!!slug"
                                       :is-address-suggestions-enabled="isAddressSuggestionsEnabled"
                                       required
                        ></address-field>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <driving-categories-dropdown v-model="driving_categories"
                                                     :drivingCategories="allDrivingCategories"
                                                     :disabled="!!slug"
                        ></driving-categories-dropdown>
                    </div>
                    <div class="column">
                        <b-field label="Тип автошколы">
                            <b-select v-model="type" placeholder="Выберите тип" expanded :disabled="!!slug">
                                <option v-for="(typeLabel, typeValue) in schoolTypes" :value="typeValue">{{ typeLabel }}</option>
                            </b-select>
                        </b-field>
                    </div>
                </div>

                <div v-if="! slug" class="buttons">
                    <button class="button" type="button" @click="goToPrevious">Назад</button>
                    <button class="button is-success" type="submit" :disabled="isSubmiting">Создать</button>
                </div>
                <div v-else class="buttons is-justify-content-flex-end">
                    <a :href="`/driving-schools/${slug}`"
                       class="button is-primary"
                    >Перейти на страницу автошколы</a>
                </div>
            </b-step-item>
        </b-steps>

        <b-loading :active="isSubmiting"></b-loading>
    </form>
</template>

<script>
import $ from 'jquery';
import 'suggestions-jquery';
import DrivingCategoriesDropdown from "./DrivingCategoriesDropdown";
import AddressField from "./AddressField";
import {showErrors} from '../_helpers';

export default {
    name: 'CreateDrivingSchool',

    components: {AddressField, DrivingCategoriesDropdown},

    props: {
        token: String,
        sessionKey: String,

        allDrivingCategories: Array,
        schoolTypes: Object,
    },

    data() {
        return {
            activeStep: 0,
            suggestInn: true,

            slug: null,

            name: '',
            legal_name: '',
            inn: '',
            type: '',
            driving_categories: [],
            address: {},

            nameMessage: '',
            legal_nameMessage: '',
            innMessage: '',
            typeMessage: '',
            driving_categoriesMessage: '',
            addressMessage: '',

            isAddressSuggestionsEnabled: true,

            isSubmiting: false,
        }
    },

    watch: {
        activeStep() {
            // На случай если пользователь решил вернуться к подсказкам
            if (this.activeStep === 0) {
                this.suggestInn = true;
            }
        },
    },

    mounted() {
        $(this.$refs.innField).suggestions({
            token: window.serverData.dadataToken,
            type: 'PARTY',
            deferRequestBy: 200,
            minChars: 2,
            onSelect: this.handlePartySuggestion,
        });
    },

    methods: {
        showErrors,

        async onSubmit() {
            // Ничего не делать если уже создана
            if (this.slug) {
                return;
            }

            this.isSubmiting = true;
            this.errors = [];

            try {
                this.slug = (await axios.post('/driving-schools', {
                    name: this.name,
                    legal_name: this.legal_name,
                    inn: this.inn,
                    type: this.type,
                    driving_categories: this.driving_categories,
                    address: this.address,
                })).data;
            } catch (error) {
                this.showLoadingError();

                if (error.response && error.response.status === 422) {
                    this.showErrors(error);
                }
            } finally {
                this.isSubmiting = false;
            }
        },

        skipSuggestion() {
            this.suggestInn = false;
            this.activeStep++;

            this.resetForm();
        },

        resetForm() {
            this.inn = '';
        },

        goToPrevious() {
            this.activeStep--;
        },

        handlePartySuggestion(suggestion) {
            this.isAddressSuggestionsEnabled = false; // Выключить чтобы не делал лишний запрос при заполнении
            this.legal_name = suggestion.data.name.short_with_opf;

            if (
                ! this.legal_name
                || suggestion.data.name.full_with_opf < 200
            ) {
                this.legal_name = suggestion.data.name.full_with_opf;
            }

            this.inn = suggestion.data.inn;
            this.address = {
                value: suggestion.data.address.value,
                ...suggestion.data.address.data,
            };
            this.activeStep++;
            this.isAddressSuggestionsEnabled = false;
        },

        showLoadingError() {
            this.$buefy.toast.open({
                duration: 10000,
                message: 'Что-то пошло не так. Попробуйте повторить запрос или обновить страницу.',
                position: 'is-top',
                type: 'is-danger',
            });
        },
    },
};
</script>

<style scoped lang="scss">
    @import "./../../sass/mixins";
    @import "./../../sass/variables";

    @import "~suggestions-jquery/dist/css/suggestions.min.css";

    .step-item {
        &:first-child {
            @include media-breakpoint-up(md) {
                width: 50%;
                margin: 0 auto;
            }

            .buttons {
                justify-content: flex-end;
            }
        }

        .buttons {
            /*Кнопки в разные стороны*/
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
    }
</style>
