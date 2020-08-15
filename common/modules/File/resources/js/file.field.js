class FileField {
    /**
     * FileField constructor
     * @param {Object} options
     * $options fields: $container, $validation
     *  {string} $container selector for searching input elements
     *  {Object} $validation for setting rules for validation of files
     *  $validation fields: $type, $mimeType, $size, $fileCount
     *  {Array} $type list of acceptable file types
     *  {Array} $mimeType list of acceptable file MIME types
     *  {number} $size maximum file size in kilobytes
     *  {number} fileCount maximum number of files attachment
     */
    constructor(options) {
        this.options = options;
        this.container = document.getElementById(options.container);
        this.fileInputs = this.container.querySelectorAll("input[type='file']");
    }

    /**
     *
     * @param {string} tagName
     * @param {string} className
     * @returns {HTMLElement}
     */
    static createElem(tagName, className) {
        const elem = document.createElement(tagName);
        elem.classList.add(className);

        return elem;
    }

    /**
     *
     * @param {string} fileName
     * @param {Array} types
     * @returns {boolean}
     */

    static isValidType(fileName, types) {
        const type = fileName.match(/\.(.+)$/)[1];

        return types.includes(type);
    }

    /**
     *
     * @param {string} fileType
     * @param {Array} types
     * @returns {boolean}
     */
    static isValidMimeType(fileType, types) {
        return types.some(type => fileType.match(type));
    }

    /**
     *
     * @param {number} size
     * @param {number} maxSize
     * @returns {boolean}
     */
    static isValidSize(size, maxSize) {
        const KILOBYTE = 1024;

        return size < maxSize * KILOBYTE;
    }

    /**
     *
     * @param {Object} file
     * @param {Object} options
     * @param {HTMLElement} error
     * @returns {boolean}
     */
    static validateFile(file, options, error) {
        if (options.type && !FileField.isValidType(file.name, options.type)) {
            error.textContent = "Формат файла должен быть " + options.type.join("/");
            return false;
        }

        if (options.mimeType && !FileField.isValidMimeType(file.type, options.mimeType)) {
            error.textContent = "MIME формат файла должен быть " + options.mimeType.join("/");
            return false;
        }

        if (options.size && !FileField.isValidSize(file.size, options.size)) {
            error.textContent = "Размер файла не должен превышать " + options.size + " килобайт";
            return false;
        }

        return true;
    }

    /**
     *
     * @param {string} name
     * @param {string} src
     * @returns {HTMLElement}
     */
    static createPreview(name, src) {
        const previewItem = FileField.createElem("div", "preview__item"),
            previewImg = FileField.createElem("img", "preview__img"),
            previewName = FileField.createElem("span", "preview__name");

        previewImg.src = src;
        previewName.textContent = name;

        previewItem.appendChild(previewImg);
        previewItem.appendChild(previewName);

        return previewItem;
    }

    /**
     *
     * @param {Object} file
     * @param {HTMLElement} previewList
     */
    initReader(file, previewList) {
        const reader = new FileReader();

        reader.onload = event => {
            previewList.appendChild(FileField.createPreview(file.name, event.target.result));
        };

        reader.readAsDataURL(file);
    }

    /**
     *
     * @param {HTMLInputElement} input
     */
    attachDefaultListener(input) {
        const files = Array.from(input.files);
        const inputContainer = input.closest(".file-input");
        let previewList = inputContainer.querySelector(".preview-list");

        if (!previewList) {
            previewList = FileField.createElem("div", "preview-list");

            inputContainer.appendChild(previewList);
        }

        if (this.options.validation) {
            let error = inputContainer.querySelector(".file__error");

            if (!error) {
                error = FileField.createElem("span", "file__error");
                inputContainer.appendChild(error);
            }

            if (this.options.validation.fileCount && files.length > this.options.validation.fileCount) {
                error.textContent = "Количество файлов не должно быть больше " + this.options.validation.fileCount;
                previewList.innerHTML = "";
                input.value = "";

                return false;
            }

            if (files.some((file) => !FileField.validateFile(file, this.options.validation, error))) {
                previewList.innerHTML = "";
                input.value = "";

                return false;
            }

            error.innerHTML = "";
        }

        previewList.innerHTML = "";

        files.forEach((file) => {
            this.initReader(file, previewList);
        });
    }

    /**
     *
     * @param {HTMLInputElement} input
     */
    attachAjaxListener(input) {
        console.log("nod done yet");
    }

    /**
     *
     * @param {HTMLInputElement} input
     */
    attachListener(input) {
        (this.options.url) ? this.attachAjaxListener(input) : this.attachDefaultListener(input);
    }

    init() {
        for (let i = 0; i < this.fileInputs.length; i++) {
            const self = this.fileInputs[i];

            self.addEventListener("change", () => this.attachListener(self));
        }
    }
}
