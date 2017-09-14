<template>
    <div>
        <h3>Edição de produto</h3>

        <div class="card horizontal">
            <div class="card-image" v-if="preview">
                <img :src="preview">
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <span class="card-title">{{ product.title }}</span>
                    <form enctype="multipart/form-data" @submit.prevent="save()">
                            <div class="input-field">
                                <input type="text" placeholder="Título..." v-model="product.title" required>
                            </div>

                            <div class="input-field">
                                <input type="text" class="form-control" placeholder="Sub-título..." v-model="product.subtitle" required>
                            </div>

                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>File</span>
                                    <input type="file" @change="imageUploader($event)">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Imagem...">
                                </div>
                            </div>

                            <div class="input-field">
                                <input type="text" placeholder="Link do produto..." v-model="product.url" required>
                            </div>

                            <input type="submit" value="salvar" class="waves-effect waves-light btn">
                        <a href="#/products" class="btn grey lighten-4 black-text">voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                preview: null
            }
        },
        methods: {
            save() {
                let formData = new FormData();
                formData.append('title', this.product.title);
                formData.append('subtitle', this.product.subtitle);
                formData.append('image_url', this.product.image_url);
                formData.append('url', this.product.url);
                formData.append('_method', 'PUT');

                this.$store.dispatch('updateProduct', {id:this.$route.params.id, data: formData}).then(() => {
                    this.$store.dispatch('getProduct', this.$route.params.id)
                    swal ("Salvo com sucesso","O produto foi atualizado sucesso", "success")
                })
            },
            imageUploader($event) {
                if ($event.target.files.length > 0) {
                    this.product.image_url = $event.target.files[0];
                }
            }
        },
        computed: {
            product () {
                return this.$store.state.product.product
            }
        },
        mounted () {
            this.$store.dispatch('getProduct', this.$route.params.id)
        }
    }
</script>