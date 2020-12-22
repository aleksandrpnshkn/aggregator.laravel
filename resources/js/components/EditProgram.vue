<template>
    <div class="edit-learning-place">
        <h1 class="title my-6">{{ isCreating ? 'Добавить программу' : 'Обновить программу' }}</h1>

        <form @submit.prevent="onSubmit">
            <div class="columns">
                <div class="column">
                    <b-field label="Название"
                             :type="nameMessage ? 'is-danger' : null"
                             :message="nameMessage"
                    >
                        <b-input v-model="name" required expanded @input="nameMessage = ''"></b-input>
                    </b-field>
                </div>
                <div class="column">
                    <b-field label="Сроки программы"
                             :type="starts_atMessage || ends_atMessage ? 'is-danger' : null"
                             :message="starts_atMessage + ' ' + ends_atMessage"
                    >
                        <b-datepicker v-model="starts_at"
                                      placeholder="Дата начала"
                                      icon="calendar"
                                      editable
                                      expanded
                                      @input="starts_atMessage = ends_atMessage = ''"
                        ></b-datepicker>
                        <b-datepicker v-model="ends_at"
                                      placeholder="Дата окончания"
                                      icon="calendar"
                                      editable
                                      expanded
                                      @input="starts_atMessage = ends_atMessage = ''"
                        ></b-datepicker>
                    </b-field>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <driving-categories-dropdown v-model="driving_categories"
                                                 :driving-categories="drivingSchool.driving_categories"
                    ></driving-categories-dropdown>
                    <p class="help">
                        Если в предложенном списке нет нужной категории -
                        <a :href="`/driving-schools/${drivingSchool.slug}/edit`">добавьте</a>
                        её в информации о вашей автошколе.
                    </p>
                </div>
                <div class="column">
                    <b-field label="Цена">
                        <b-input v-model="price" type="number" step=".01" expanded></b-input>
                        <p class="control">
                            <span class="button is-static">₽</span>
                        </p>
                        <b-select v-model="price_type" :required="!!price" expanded>
                            <option v-for="(priceTypeLabel, priceTypeValue) in priceTypes" :value="priceTypeValue">{{ priceTypeLabel }}</option>
                        </b-select>
                    </b-field>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <b-field label="Учебные места"></b-field>
                    <div v-if="drivingSchool.learning_places.length">
                        <b-field v-for="learningPlace in drivingSchool.learning_places"
                                 :key="learningPlace.id"
                        >
                            <b-checkbox v-model="learning_places" :native-value="learningPlace.id">
                                {{ learningPlace.type_label }} - {{ learningPlace.address.value }}
                            </b-checkbox>
                        </b-field>
                    </div>
                    <p v-else class="mb-1">Вы еще не <a :href="`/driving-schools/${drivingSchool.slug}/learning-places/create`">добавили</a> ни одного учебного места.</p>
                </div>
                <div class="column">
                    <p class="label">Прочее</p>
                    <b-field>
                        <b-switch v-model="is_akpp">АКПП</b-switch>
                    </b-field>
                    <b-field>
                        <b-switch v-model="is_retraining">Переподготовка</b-switch>
                    </b-field>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <b-field label="Описание">
                        <b-input v-model="description" type="textarea"></b-input>
                    </b-field>
                </div>
            </div>

            <div class="buttons is-right">
                <button v-if="! isCreating" class="button is-danger" type="button" @click="confirmDelete">Удалить</button>
                <button class="button is-success" type="submit">{{ isCreating ? 'Сохранить' : 'Обновить' }}</button>
            </div>
        </form>

        <b-loading :active="isSubmiting" is-full-page></b-loading>
    </div>
</template>

<script>
import { showErrors } from '../_helpers';
import DrivingCategoriesDropdown from './DrivingCategoriesDropdown';

export default {
    name: 'EditProgram',

    components: {DrivingCategoriesDropdown},

    props: {
        drivingSchool: Object,
        program: Object,
        priceTypes: Object,
    },

    data() {
        return {
            id: null,
            name: '',
            starts_at: null,
            ends_at: null,
            price: '',
            price_type: '',
            driving_categories: [],
            learning_places: [],
            is_akpp: false,
            is_retraining: false,
            description: '',

            nameMessage: '',
            starts_atMessage: '',
            ends_atMessage: '',
            priceMessage: '',
            learning_placesMessage: '',
            descriptionMessage: '',

            isSubmiting: false,
        };
    },

    computed: {
        isCreating() {
            return !this.id;
        }
    },

    created() {
        if (this.program) {
            this.id = this.program.id;
            this.name = this.program.name;
            this.starts_at = this.program.starts_at ? new Date(this.program.starts_at) : null;
            this.ends_at = this.program.ends_at ? new Date(this.program.ends_at) : null;
            this.price = this.program.price;
            this.price_type = this.program.price_type;
            this.driving_categories = this.program.driving_categories.map((category) => category.id);
            this.learning_places = this.program.learning_places.map((place) => place.id);
            this.is_akpp = Boolean(this.program.is_akpp);
            this.is_retraining = Boolean(this.program.is_retraining);
            this.description = this.program.description;
        }
    },

    methods: {
        showErrors,

        async onSubmit() {
            this.isSubmiting = true;

            try {
                this.id = (await axios.post(`/driving-schools/${this.drivingSchool.slug}/programs`, {
                    id: this.id,
                    name: this.name,
                    // b-datepicker использует Date и отправит на сервер соответственно Date.toString(),
                    // который учитывает пояс и по итогу отправляет UTC дату, где день может отличаться от выбранного.
                    starts_at: this.starts_at ? this.starts_at.toLocaleDateString() : null,
                    ends_at: this.ends_at ? this.ends_at.toLocaleDateString() : null,
                    price: this.price,
                    price_type: this.price_type,
                    driving_categories: this.driving_categories,
                    learning_places: this.learning_places,
                    is_akpp: this.is_akpp,
                    is_retraining: this.is_retraining,
                    description: this.description,
                })).data;

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: 'Изменения сохранены',
                });

                // Хотя, как вариант можно возвращать на index
                history.pushState(null, '', `/driving-schools/${this.drivingSchool.slug}/programs/${this.id}/edit`);
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось сохранить, попробуйте еще раз.',
                });

                if (error.response && error.response.status === 422) {
                    this.showErrors(error);
                }
            } finally {
                this.isSubmiting = false;
            }
        },

        confirmDelete() {
            this.$buefy.dialog.confirm({
                title: 'Удаление программы',
                message: 'Вы действительно хотите удалить выбранную программу?',
                confirmText: 'Удалить',
                cancelText: 'Отменить',
                type: 'is-danger',
                onConfirm: this.deleteProgram,
            });
        },

        async deleteProgram() {
            this.isSubmiting = true;

            try {
                await axios.delete(`/driving-schools/${this.drivingSchool.slug}/programs/${this.id}`);
                document.location = `/driving-schools/${this.drivingSchool.slug}/programs`;
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось удалить',
                });
            } finally {
                this.isSubmiting = true;
            }
        },
    },
};
</script>

<style scoped lang="scss">

</style>
