<template>
    <div>
        <h1 class="title">Учебные места</h1>

        <div class="buttons is-right">
            <a :href="`/driving-schools/${drivingSchool.slug}/learning-places/create`" class="button is-primary">Добавить новое место</a>
        </div>

        <b-table v-if="learningPlaces.length" :data="learningPlaces" bordered detailed detail-key="id">
            <b-table-column label="Тип" v-slot="props">{{ props.row.type_label }}</b-table-column>
            <b-table-column label="Адрес" v-slot="props">{{ props.row.address.value }}</b-table-column>
            <b-table-column label="Создано" v-slot="props">{{ props.row.created_at }}</b-table-column>
            <b-table-column label="Посл. изменение" v-slot="props">{{ props.row.updated_at }}</b-table-column>
            <b-table-column label="" v-slot="props">
                <div class="buttons has-addons is-centered">
                    <a class="button is-primary is-small" :href="`/driving-schools/${drivingSchool.slug}/learning-places/${props.row.id}/edit`">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="button is-danger is-small" type="button" @click="confirmDelete(props.row.id)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </b-table-column>

            <template slot="detail" slot-scope="props">
                <h3 class="title is-size-6 mb-3">Описание</h3>
                {{ props.row.description }}
            </template>
        </b-table>
        <p v-else>Вы пока не добавили ни одного учебного места</p>

        <b-loading :active="isSubmiting" is-full-page></b-loading>
    </div>
</template>

<script>
export default {
    name: 'LearningPlacesTable',

    props: {
        drivingSchool: Object,
        placeTypes: Object,
    },

    data() {
        return {
            learningPlaces: [...this.drivingSchool.learning_places],
            isSubmiting: false,
        };
    },

    methods: {
        confirmDelete(id) {
            this.$buefy.dialog.confirm({
                title: 'Удаление учебного места',
                message: 'Вы действительно хотите удалить выбранное место?',
                confirmText: 'Удалить',
                cancelText: 'Отменить',
                type: 'is-danger',
                onConfirm: () => this.deletePlace(id),
            });
        },

        async deletePlace(id) {
            this.isSubmiting = true;

            try {
                await axios.delete(`/driving-schools/${this.drivingSchool.slug}/learning-places/${id}`);

                const index = this.learningPlaces.findIndex((learningPlace) => learningPlace.id === id);
                this.learningPlaces.splice(index, 1);

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: 'Успешно удалено!',
                });
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось удалить выбранное место',
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
