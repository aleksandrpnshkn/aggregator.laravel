<template>
    <a class="driving-school-preview"
       :href="`/driving-schools/${drivingSchool.slug}`"
    >
        <div class="driving-school-preview__container box">
            <div class="driving-school-preview__main">
                <div>
                    <template v-if="drivingSchool.name">
                        <h2 class="driving-school-preview__title">{{ drivingSchool.name }}</h2>
                        <small class="mute">{{ drivingSchool.legal_name }}</small>
                    </template>
                    <template v-else>
                        <h2 class="driving-school-preview__title">{{ drivingSchool.legal_name }}</h2>
                    </template>
                </div>
                <div class="driving-school__info">
                    <p v-if="drivingSchool.driving_categories_string">
                        <b>Водительские категории</b>: {{ drivingSchool.driving_categories_string }}
                    </p>
                    <p><b>Адрес</b>: {{ drivingSchool.address.value }}</p>
                </div>
            </div>
            <div class="driving-school-preview__logo">
                <img v-if="drivingSchool.logo"
                     :src="drivingSchool.logo.path"
                     :alt="drivingSchool.name || drivingSchool.legal_name">
                <i v-else class="fas fa-image"></i>
            </div>
        </div>
    </a>
</template>

<script>
export default {
    name: 'DrivingSchoolPreview',

    props: ['drivingSchool'],

    methods: {

    },
};
</script>

<style scoped lang="scss">
@import "./../../sass/mixins";
@import "./../../sass/variables";

.driving-school-preview {
    display: block;

    > .box {
        @include media-breakpoint-up(lg) {
            transition: background-color .2s;

            @include hover-focus-active() {
                background-color: lighten(#7957d5, 40%);
            }
        }
    }
}

.driving-school-preview__container {
    display: grid;
    grid-template-columns: 1fr 80px;
    grid-gap: 20px;

    @include media-breakpoint-up(md) {
        grid-template-columns: 1fr 120px;
    }

    @include media-breakpoint-up(lg) {
        grid-template-columns: 1fr 160px;
    }
}

.driving-school-preview__title {
    margin-bottom: 6px;

    font-weight: bold;
    font-size: 18px;

    @include media-breakpoint-up(md) {
        font-size: 20px;
    }

    &:not(:last-child) { // Когда name && legal_name
        margin-bottom: 0;
    }
}

.driving-school__info {
    font-size: .9em;
    padding: 10px;

    @include media-breakpoint-up(md) {
        padding: 20px;
    }

    p {
        margin-bottom: 2px;

        @include media-breakpoint-up(md) {
            margin-bottom: 4px;
        }
    }
}

.driving-school-preview__logo {
    overflow: hidden;

    display: flex;
    justify-content: center;
    align-items: center;
    width: 160px;
    height: 160px;

    border-radius: 4px;
    background-color: $grey-lighter;

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    // placeholder
    i {
        font-size: 60px;
    }
}
</style>
