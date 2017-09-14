<template>
    <div>
        <h3>Remover Produto</h3>

        <div class="card orange white-text">
            <div class="card-content">
                Você não poderá desfazer esta ação!
            </div>
            <p>
                <a href="" class="btn red" @click.prevent="remove()">Quero remover!</a>
                <a href="#/products" class="btn blue">Não quero remover</a>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            remove: function () {
                this.$store.dispatch('removeProduct', this.product.id).then(() => {
                    this.$router.push({path: '/products'});
                });
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