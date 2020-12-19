/**
 * Разобрать объект ошибки и присвоить сообщения об ошибке field в fieldMessage
 * @this Vue
 */
function showErrors(error) {
    Object.entries(error.response.data.errors)
        .forEach(([field, messages]) => {
            if (/[A-z_0-9]/.test(field)) {
                this[field + 'Message'] = messages.join('<br>');
            }
        });
}

export {
    showErrors,
};
