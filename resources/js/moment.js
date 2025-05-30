import moment from 'moment';
import 'moment/locale/id';
import 'moment/locale/en-gb';

const locale = document.documentElement.lang || 'id';

moment.updateLocale('id', {
    relativeTime: {
        future: "dalam %s",
        past: "%s yang lalu",
        s: "beberapa detik",
        m: "semenit",
        mm: "%d menit",
        h: "sejam",
        hh: "%d jam",
        d: "sehari",
        dd: "%d hari",
        M: "sebulan",
        MM: "%d bulan",
        y: "setahun",
        yy: "%d tahun"
    }
});

moment.locale(locale);

window.moment = moment;
