<template>
    <section class="box">
        <h2 class="title">{{ this.id ? 'Редактировать контакт' : 'Добавить контакт' }}</h2>

        <form @submit.prevent="onSubmit">
            <div class="columns">
                <div class="column">
                    <b-field label="Тип"
                             requred
                             :message="contact_typeMessage"
                             :type="contact_typeMessage && 'is-danger'"
                    >
                        <b-select v-model="contact_type" expanded @input="contact_typeMessage = ''; valueMessage = ''">
                            <option v-for="(typeLabel, typeValue) in contactTypes" :value="typeValue">{{ typeLabel }}</option>
                        </b-select>
                    </b-field>
                </div>
                <div class="column">
                    <b-field label="Значение"
                             expanded
                             requred
                             :message="valueMessage || valueHelpText"
                             :type="valueMessage && 'is-danger'"
                    >
                        <b-input v-model="value"
                                 type="text"
                                 @input="valueMessage = ''"
                                 :placeholder="valuePlaceholder"
                        ></b-input>
                    </b-field>
                </div>
            </div>

            <div class="buttons is-right">
                <button class="button is-primary">{{ this.id ? 'Обновить' : 'Сохранить' }}</button>
            </div>

            <b-loading :active="isSubmiting"></b-loading>
        </form>
    </section>
</template>

<script>
import { showErrors } from '../_helpers';

export default {
    name: 'EditContact',

    props: {
        drivingSchoolSlug: {
            type: String,
            required: true,
        },
        contactTypes: {
            type: Object,
            required: true,
        },
        contact: Array,
    },

    data() {
        return {
            id: null,
            contact_type: '',
            value: '',

            contact_typeMessage: '',
            valueMessage: '',

            isSubmiting: false,
        };
    },

    computed: {
        valuePlaceholder() {
            return {
                url: 'https://example.com',
                email: 'email@example.com',
                tg: '@username',
                phone: '+7 800 123 45 67',
                viber: '+7 800 123 45 67',
                whatsapp: '+7 800 123 45 67',
            }[this.contact_type] || '';
        },

        valueHelpText() {
            return {
                tg: 'Логин должен быть длинной 5-32 символа и может содержать в себе только латинские буквы, цифры и символы @ и _.',
                viber: 'Номер должен быть в международном формате.',
                whatsapp: 'Номер должен быть в международном формате.',
            }[this.contact_type] || '';
        },
    },

    created() {
        if (this.contact) {
            this.id = this.contact.id;
            this.contact_type = this.contact.contact_type;
            this.value = this.contact.value;
        }
    },

    methods: {
        async onSubmit() {
            this.isSubmiting = true;

            try {
                this.id = (await axios.post(`/driving-schools/${this.drivingSchoolSlug}/contacts`, {
                    id: this.id,
                    contact_type: this.contact_type,
                    value: this.value,
                })).data;

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: `Успешно сохранено`,
                });

                this.$emit('update', {
                    id: this.id,
                    contact_type: this.contact_type,
                    contact_type_label: this.contactTypes[this.contact_type], // Лейбл для таблицы
                    value: this.value,
                });

            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: `Не удалось ${this.id ? 'обновить' : 'сохранить'} контакт`,
                });

                if (error.response && error.response.status === 422) {
                    // Внутри функции понадобится this компонента чтобы менять data
                    showErrors.bind(this)(error);
                }

            } finally {
                this.isSubmiting = false;
            }
        },
    },
};
</script>

<style scoped lang="scss">

</style>
