import moment from "moment";

// Convert 'DD/MM/YYYY HH:mm' → 'YYYY-MM-DD HH:mm'
export const toDatabaseFormat = (value) => {
    return moment(value).format("YYYY-MM-DD HH:mm");
};


// Convert 'YYYY-MM-DD HH:mm:ss' → 'DD/MM/YYYY HH:mm'
export const toDisplayFormat = (value) => {
    return moment(value).format("YYYY-MM-DDTHH:mm");
};
