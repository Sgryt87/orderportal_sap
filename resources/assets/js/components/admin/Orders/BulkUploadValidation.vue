<script>

    export default {
        validationErrors: [],

        validate(data) {
            this.validationErrors = [];
            if (!this.validateHeaders(data))
                return false;

            if (!this.validateDuplicates(data))
                return false;

            return true;
        },

        validateHeaders(data) {

            const columnHeaders = [
                'NSN',
                '# of PreSells',
                '# of Order Boards',
                'Protective Covers',
                'Height Requirements',
                'Requested Enclosure Delivery Date',
                'Delivery Notes'
            ];

            if (columnHeaders.length != Object.keys(data[0]).length) {
                console.log(columnHeaders.length, '  ', Object.keys(data[0]).length);
                this.validationErrors.push(`File format is incorrect. Please verify you are using the latest upload template.`);
                return false;
            }

            for (let i = 0; i < columnHeaders.length; i++) {
                if (!data[0].hasOwnProperty(columnHeaders[i])) {
                    this.validationErrors.push(`File format is incorrect. Please verify you are using the latest upload template. Column header ${columnHeaders[i]} wasn't found`);
                    return false;
                }
            }
            return true;
        },

        validateDuplicates(data) {

            function isEquivalent(a, b) {
                // Create arrays of property names
                let aProps = Object.getOwnPropertyNames(a);
                let bProps = Object.getOwnPropertyNames(b);

                // If number of properties is different,
                // objects are not equivalent
                if (aProps.length !== bProps.length) {
                    return false;
                }

                for (let i = 0; i < aProps.length; i++) {
                    let propName = aProps[i];

                    // If values of same property are not equal,
                    // objects are not equivalent
                    if (a[propName] !== b[propName]) {
                        return false;
                    }
                }

                // If we made it this far, objects
                // are considered equivalent
                return true;
            }

            for (let i = 0; i < data.length; i++) {
                for (let j = i + 1; j < data.length; j++) {
                    if (isEquivalent(data[i], data[j])) {
                        this.errors.push(`Duplicated data: ${j} in row: ${i}`);
                        return false;
                    }
                }
            }
            return true;
        }

    }
</script>

