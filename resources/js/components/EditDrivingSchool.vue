<template>
    <form class="my-4" @submit.prevent="onSubmit">
        <h2 class="title has-text-centered my-6">Редактировать автошколу</h2>

        <div class="columns">
            <div class="column">
                <b-field label="Название"
                         :type="nameMessage ? 'is-danger' : null"
                         :message="nameMessage"
                >
                    <b-input v-model="name" @input="nameMessage = ''"></b-input>
                </b-field>
            </div>
            <div class="column">
                <b-field label="Полное наименование организации"
                         :type="legal_nameMessage ? 'is-danger' : null"
                         :message="legal_nameMessage"
                         custom-class="required"
                >
                    <b-input v-model="legal_name" required @input="legal_nameMessage = ''"></b-input>
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
                    <b-input v-model="inn" type="number" required @input="innMessage = ''"></b-input>
                </b-field>
            </div>
            <div class="column">
                <address-field v-model="address" :addressMessage="addressMessage" required></address-field>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <driving-categories-dropdown v-model="driving_categories"
                                             :drivingCategories="allDrivingCategories"
                ></driving-categories-dropdown>
            </div>
            <div class="column">
                <b-field label="Тип автошколы">
                    <b-select v-model="type" placeholder="Выберите тип" expanded>
                        <option v-for="(typeLabel, typeValue) in schoolTypes" :value="typeValue">{{ typeLabel }}</option>
                    </b-select>
                </b-field>
            </div>
        </div>

        <div class="buttons is-right">
            <button class="button is-small is-danger" type="button" @click="confirmDelete">Удалить</button>
            <button class="button is-success" type="submit" :disabled="isSubmiting">Обновить</button>
        </div>

        <b-loading :active="isSubmiting"></b-loading>
    </form>
</template>

<script>
import DrivingCategoriesDropdown from "./DrivingCategoriesDropdown";
import AddressField from "./AddressField";
import { showErrors } from '../_helpers';

export default {
    name: 'EditDrivingSchool',

    components: {AddressField, DrivingCategoriesDropdown},

    props: {
        drivingSchool: Object,
        allDrivingCategories: Array,
        schoolTypes: Object,
    },

    data() {
        return {
            slug: '',
            name: '',
            legal_name: '',
            inn: '',
            address: {},
            driving_categories: [],
            type: '',

            nameMessage: '',
            legal_nameMessage: '',
            innMessage: '',
            addressMessage: '',
            driving_categoriesMessage: '',
            typeMessage: '',

            isSubmiting: false,
        }
    },

    created() {
        this.slug = this.drivingSchool.slug;
        this.name = this.drivingSchool.name;
        this.legal_name = this.drivingSchool.legal_name;
        this.inn = this.drivingSchool.inn;
        this.address = this.drivingSchool.address;
        this.addressData = this.drivingSchool.address;
        this.driving_categories = this.drivingSchool.driving_categories.map((category) => category.id);
        this.type = this.drivingSchool.type;
    },

    methods: {
        showErrors,

        async onSubmit() {
            this.isSubmiting = true;

            try {
                await axios.patch(`/driving-schools/${this.slug}`, {
                    name: this.name,
                    legal_name: this.legal_name,
                    inn: this.inn,
                    type: this.type,
                    driving_categories: this.driving_categories,
                    address: this.address,
                });

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: 'Автошкола успешно обновлена!',
                });
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось обновить!',
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
                title: 'Удаление автошколы',
                message: `Вы действительно хотите удалить автошколу <b>${this.name || this.legal_name}</b>?`,
                confirmText: 'Удалить',
                cancelText: 'Отменить',
                type: 'is-danger',
                onConfirm: this.deleteSchool,
            });
        },

        async deleteSchool() {
            this.isSubmiting = true;

            try {
                await axios.delete(`/driving-schools/${this.slug}`);
                location.href = '/profile';
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось удалить, попробуйте еще раз',
                });
            } finally {
                this.isSubmiting = false;
            }
        },
    },
};
</script>

<style scoped lang="scss">

</style>
