//here we add any helper functions used common in the front end app

export const copyObject = (obj) => {
    return JSON.parse(JSON.stringify(obj));
};

export const getRandomColor = (colors =  []) => {
    let letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }

    //if provided an array of existing colors we make sure the output color doesn't exist on it
    if(colors.includes(color)){
        this.getRandomColor();
    }

    return color;
};

export const isFloat = (value) => {
    return !isNaN(parseFloat(value));
};
