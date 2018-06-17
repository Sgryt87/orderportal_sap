import holidaysJSON from './holidaysJSON';

export default {

    disabledDays(days) {
        let res = holidaysJSON.map(function (k) {
            return {
                start: k['date'],
                end: k['date']
            };
        });
        res.push({weekdays: [1, 7]});
        return res;
    }
}