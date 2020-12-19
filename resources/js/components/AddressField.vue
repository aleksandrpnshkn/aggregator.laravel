<template>
    <div class="field">
        <label class="control">
            <span :class="`label ${required ? 'required' : ''}`">Адрес</span>
            <input v-model="input"
                   ref="addressField"
                   :class="`input ${addressMessage ? 'is-danger' : ''}`"
                   type="text"
                   :required="required"
                   :disabled="disabled"
                   @input="resetAddressMessage">
        </label>
        <p v-if="addressMessage" class="help is-danger">{{ addressMessage }}</p>
    </div>
</template>

<script>
import $ from 'jquery';
import 'suggestions-jquery';

export default {
    name: 'AddressField',

    props: {
        value: Object,
        addressMessage: {
            type: String,
            default: '',
        },
        required: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        isAddressSuggestionsEnabled: {
            type: Boolean,
            default: true,
        },
    },

    computed: {
        input: {
            /**
             * Нужно присваивать addressData перед input!
             */
            set(value) {
                // Очистить addressData если данные не соответствуют введённому адресу
                if (
                    this.addressDataFor &&
                    value.trim() !== this.addressDataFor.trim()
                ) {
                    this.addressDataFor = '';
                    this.addressData = {};
                }

                this.$emit('input', {
                    ...this.addressData, // в addressData будет старый value
                    value,
                });
            },

            get() {
                // На случай если данные присваиваются извне, как в CreateDrivingSchool
                this.addressData = this.value;
                this.addressDataFor = this.value.value;

                return this.value.value || '';
            },
        },
    },

    data() {
        return {
            addressData: {},
            addressDataFor: '',
            $addressSuggestions: null,
        };
    },

    watch: {
        isAddressSuggestionsEnabled(isEnabled) {
            if (isEnabled) {
                this.$addressSuggestions.enable();
            }
            else {
                this.$addressSuggestions.disable();
            }
        },
    },

    mounted() {
        this.$addressSuggestions = $(this.$refs.addressField).suggestions({
            token: window.serverData.dadataToken,
            type: 'ADDRESS',
            deferRequestBy: 200,
            onSelect: this.handleAddressSuggestion,
        }).suggestions();
    },

    methods: {
        handleAddressSuggestion(suggestion) {
            this.addressData = suggestion.data;
            this.addressDataFor = suggestion.value;
            this.input = suggestion.value;
        },

        resetAddressMessage() {
            this.$emit('update:addressMessage', '');
        },
    },
}
</script>

<style scoped lang="scss">
    @import "~suggestions-jquery/dist/css/suggestions.min.css";
</style>
