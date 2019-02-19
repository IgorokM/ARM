export default class List {
    /**
     * @param {HTMLElement} parent
     */
    constructor(parent) {
        this.parent = parent;
        this.ul = document.createElement('ul');
        this.ul.classList.add('collection');
    }

    render(data) {
        if (data instanceof Array) {
            for (let obj of data) {
                this.ul.appendChild(this.createItem(obj));
            }
        } else if (data instanceof Object) {
            this.ul.appendChild(this.createItem(data));
        }
        this.parent.appendChild(this.ul);
    }
    /**
     * @param {object} item 
     */
    createItem(item) {
        let li = document.createElement('li');
        li.className = 'collection-item avatar';

        let img = document.createElement('img');
        img.src = item.img;
        img.className = 'circle';
        li.appendChild(img);

        let title = document.createElement('p');
        title.textContent = `Parts: ${item.name}`;
        title.className = 'title';
        li.appendChild(title);

        let p = document.createElement('p');
        p.textContent = `Width: ${item.width}`;
        li.appendChild(p);
        return li;
    }
}