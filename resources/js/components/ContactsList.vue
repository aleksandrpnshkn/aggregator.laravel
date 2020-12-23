<template>
    <div class="contacts-list">
        <ul>
            <li class="contact" v-for="contact in contacts">
                <a :href="contact.href" :title="contact.contact_type_label" target="_blank" rel="nofollow noopener">
                    <span class="contact__icon" v-html="getIcon(contact.contact_type)"></span>
                    <span class="contact__value">{{ contact.value }}</span>
                </a>
                <div v-if="canEdit" class="contact__buttons buttons has-addons">
                    <button class="button is-small is-primary" type="button" @click="showContactModal(contact)">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="button is-small is-danger" type="button" @click="confirmDeleteContact(contact.id)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </li>
        </ul>
        <div v-if="canEdit" class="buttons is-centered mt-2">
            <button class="button is-primary is-small" type="button" @click="() => showContactModal()">Добавить контакт</button>
        </div>
    </div>
</template>

<script>
import EditContact from './EditContact';

export default {
    name: 'ContactsList',

    props: {
        drivingSchoolSlug: {
            type: String,
            required: true,
        },
        initialContacts: {
            type: Array,
            required: true,
        },
        contactTypes: {
            type: Object,
            required: true,
        },
        canEdit: {
            type: Boolean,
            required: true,
        },
    },

    data() {
        return {
            contacts: [...this.initialContacts],
            isSubmiting: false,
        }
    },

    methods: {
        getIcon(contactType) {
            return {
                url: '<i class="fas fa-external-link-alt"></i>',
                whatsapp: '<i class="fab fa-whatsapp"></i>',
                viber: '<i class="fab fa-viber"></i>',
                tg: '<i class="fab fa-telegram-plane"></i>',
                email: '<i class="fas fa-at"></i>',
                phone: '<i class="fas fa-phone"></i>',
            }[contactType] || '';
        },

        showContactModal(contact) {
            const contactModal = this.$buefy.modal.open({
                component: EditContact,
                props: {
                    drivingSchoolSlug: this.drivingSchoolSlug,
                    contactTypes: this.contactTypes,
                    contact,
                },
                events: {
                    update: (updatedContact) => {
                        // Проверить что такой контакт уже есть. Если есть - обновить, иначе добавить
                        const index = this.contacts.findIndex((contact) => contact.id === updatedContact.id);

                        if (index === -1) {
                            this.contacts.push(updatedContact);
                        }
                        else {
                            // Если просто поменять по индексу - не обновит, т.к. не следит за изменениями элементов массива
                            this.contacts = this.contacts.map((contact, i) => {
                                if (i === index) {
                                    return updatedContact;
                                }
                                return contact;
                            });
                        }

                        contactModal.close();
                    },
                },
            });
        },

        confirmDeleteContact(id) {
            this.$buefy.dialog.confirm({
                title: 'Удаление автошколы',
                message: 'Вы действительно хотите удалить данный контакт?',
                confirmText: 'Удалить',
                cancelText: 'Отменить',
                type: 'is-danger',
                onConfirm: () => this.deleteContact(id),
            });
        },

        async deleteContact(id) {
            this.isSubmiting = true;

            try {
                await axios.delete(`/driving-schools/${this.drivingSchoolSlug}/contacts/${id}`);

                const index = this.contacts.findIndex((contact) => contact.id === id);
                this.contacts.splice(index, 1);

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: 'Успешно удалено!',
                });
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось удалить контакт',
                });
            } finally {
                this.isSubmiting = false;
            }
        },
    },
};
</script>

<style scoped lang="scss">
    .contact {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
    }

    .contact__icon {
        flex-shrink: 0;
        margin-right: 4px;
    }

    .contact__buttons {
        flex-shrink: 0;
        margin-left: auto;
    }
</style>
