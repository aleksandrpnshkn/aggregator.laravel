<template>
    <div class="edit-learning-place">
        <h1 class="title my-6">{{ isCreating ? 'Добавить учебное место' : 'Обновить учебное место' }}</h1>

        <form @submit.prevent="onSubmit">
            <div class="columns">
                <div class="column">
                    <b-field label="Тип" custom-class="required">
                        <b-select v-model="type" required expanded>
                            <option v-for="(typeLabel, typeValue) in placeTypes" :value="typeValue">{{ typeLabel }}</option>
                        </b-select>
                    </b-field>
                </div>
                <div class="column">
                    <address-field v-model="address" required></address-field>
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
import AddressField from './AddressField';
import { showErrors } from '../_helpers';

export default {
    name: 'EditLearningPlace',

    components: {AddressField},

    props: {
        drivingSchool: Object,
        learningPlace: Object,
        placeTypes: Object,
    },

    data() {
        return {
            id: null,
            type: '',
            address: {},
            description: '',

            isSubmiting: false,
        };
    },

    computed: {
        isCreating() {
            return !this.id;
        }
    },

    created() {
        if (this.learningPlace) {
            this.id = this.learningPlace.id;
            this.type = this.learningPlace.type ;
            this.address = this.learningPlace.address;
            this.description = this.learningPlace.description;
        }
    },

    methods: {
        showErrors,

        async onSubmit() {
            this.isSubmiting = true;

            try {
                this.id = (await axios.post(`/driving-schools/${this.drivingSchool.slug}/learning-places`, {
                    id: this.id,
                    type: this.type,
                    address: this.address,
                    description: this.description,
                })).data;

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: 'Изменения сохранены',
                });

                // Хотя, как вариант можно возвращать на index
                history.pushState(null, '', `/driving-schools/${this.drivingSchool.slug}/learning-places/${this.id}/edit`);
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось сохранить, попробуйте еще раз.',
                });

                if (error.response && error.response.status === 422) {
                    this.showErrors(error);
                }
            }

            this.isSubmiting = false;
        },

        confirmDelete() {
            this.$buefy.dialog.confirm({
                title: 'Удаление учебного места',
                message: 'Вы действительно хотите удалить выбранное место?',
                confirmText: 'Удалить',
                cancelText: 'Отменить',
                type: 'is-danger',
                onConfirm: this.deletePlace,
            });
        },

        async deletePlace() {
            this.isSubmiting = true;

            try {
                await axios.delete(`/driving-schools/${this.drivingSchool.slug}/learning-places/${this.id}`);
                document.location = `/driving-schools/${this.drivingSchool.slug}/learning-places`;
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось удалить',
                });
            }

            this.isSubmiting = true;
        },
    },
};
</script>

<style scoped lang="scss">

</style>
