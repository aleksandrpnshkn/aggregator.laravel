<template>
    <div>
        <h1 class="title">Программы</h1>

        <div class="buttons is-right">
            <a :href="`/driving-schools/${drivingSchool.slug}/programs/create`" class="button is-primary">Добавить новую программу</a>
        </div>

        <b-table v-if="programs.length" :data="programs" bordered detailed detail-key="id">
            <b-table-column label="Название" v-slot="props">{{ props.row.name }}</b-table-column>
            <b-table-column label="Категории" v-slot="props">{{ drivingCategoriesToString(props.row.driving_categories) }}</b-table-column>
            <b-table-column label="АКПП" v-slot="props">{{ props.row.is_akpp ? 'Да' : 'Нет' }}</b-table-column>
            <b-table-column label="Переподготовка" v-slot="props">{{ props.row.is_retraining ? 'Да' : 'Нет' }}</b-table-column>
            <b-table-column label="Цена" v-slot="props">{{ props.row.price_with_type }}</b-table-column>
            <b-table-column label="Начало" v-slot="props">{{ (new Date(props.row.starts_at)).toLocaleDateString() }}</b-table-column>
            <b-table-column label="Окончание" v-slot="props">{{ (new Date(props.row.ends_at)).toLocaleDateString() }}</b-table-column>
            <b-table-column label="" v-slot="props">
                <div class="buttons has-addons is-centered">
                    <a class="button is-primary is-small" :href="`/driving-schools/${drivingSchool.slug}/programs/${props.row.id}/edit`">
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
                <hr>
                <div class="columns">
                    <p class="column"><b>Создано:</b> {{ (new Date(props.row.created_at)).toLocaleString() }}</p>
                    <p class="column"><b>Последнее изменение:</b> {{ (new Date(props.row.updated_at)).toLocaleString() }}</p>
                </div>
            </template>
        </b-table>
        <p v-else>Вы пока не добавили ни одной программы</p>

        <b-loading :active="isSubmiting" is-full-page></b-loading>
    </div>
</template>

<script>
export default {
    name: 'ProgramsTable',

    props: {
        drivingSchool: Object,
        priceTypes: Object,
    },

    data() {
        return {
            programs: [...this.drivingSchool.programs],
            isSubmiting: false,
        };
    },

    methods: {
        confirmDelete(id) {
            this.$buefy.dialog.confirm({
                title: 'Удаление программы',
                message: 'Вы действительно хотите удалить выбранную программу?',
                confirmText: 'Удалить',
                cancelText: 'Отменить',
                type: 'is-danger',
                onConfirm: () => this.deleteProgram(id),
            });
        },

        async deleteProgram(id) {
            this.isSubmiting = true;

            try {
                await axios.delete(`/driving-schools/${this.drivingSchool.slug}/programs/${id}`);

                const index = this.programs.findIndex((program) => program.id === id);
                this.programs.splice(index, 1);

                this.$buefy.toast.open({
                    type: 'is-success',
                    message: 'Успешно удалено!',
                });
            } catch (error) {
                this.$buefy.toast.open({
                    type: 'is-danger',
                    message: 'Не удалось удалить выбранную программу',
                });
            }

            this.isSubmiting = false;
        },

        drivingCategoriesToString(drivingCategories) {
            return drivingCategories.map((category) => category.name).join(', ');
        },
    },
};
</script>

<style scoped lang="scss">

</style>
