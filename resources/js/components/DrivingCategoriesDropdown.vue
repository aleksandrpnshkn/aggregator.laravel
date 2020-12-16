<template>
    <b-field label="Водительские категории">
        <b-dropdown v-if="drivingCategories.length"
                    v-model="selectedDrivingCategories"
                    class="driving-categories-dropdown"
                    multiple
                    aria-role="list"
                    expanded
                    :disabled="disabled"
        >
            <button
                class="button navbar-link is-fullwidth"
                type="button"
                slot="trigger"
            >
                <span>{{ selectedDrivingCategoriesNames || 'Выберите категории' }}</span>
            </button>

            <b-field v-if="drivingCategories.length" grouped group-multiline>
                <b-checkbox-button v-for="drivingCategory in drivingCategories"
                                   v-model="selectedDrivingCategories"
                                   :key="drivingCategory.id"
                                   :native-value="drivingCategory.id"
                >{{ drivingCategory.name }}</b-checkbox-button>
            </b-field>
        </b-dropdown>
    </b-field>
</template>

<script>
export default {
    name: 'DrivingCategoriesDropdown',

    props: {
        drivingCategories: Array,
        value: Array,
        disabled: Boolean,
    },

    computed: {
        selectedDrivingCategories: {
            get() {
                return this.value;
            },

            set(value) {
                this.$emit('input', value);
            },
        },

        selectedDrivingCategoriesNames() {
            return this.drivingCategories.filter((drivingCategory) => {
                return this.selectedDrivingCategories.includes(drivingCategory.id);
            })
                .map((drivingCategory) => drivingCategory.name)
                .join(', ');
        },
    },
};
</script>

<style scoped lang="scss">

</style>

<style lang="scss">
    .driving-categories-dropdown {
        .dropdown-content {
            padding: .5rem;
        }
    }
</style>

