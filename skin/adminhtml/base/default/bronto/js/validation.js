Validation.add(
    'validate-bronto-customer-field',
    'Field names are case insensitive, can only contain letters, numbers, and underscores, and must be 1-25 characters in length',
    function (val) {
        var pattern = /^[a-z_\d]{1,25}$/i;
        return pattern.exec(val) !== null;
    }
);