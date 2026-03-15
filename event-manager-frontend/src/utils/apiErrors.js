export function getErrorMessage(err, fallback = 'An error has occurred.') {
    return (
        err?.response?.data?.message ||
        fallback
    )
}

export function getValidationErrors(err) {
    return err?.response?.data?.errors || {}
}

export function firstFieldError(errors, field) {
    return errors?.[field]?.[0] || ''
}