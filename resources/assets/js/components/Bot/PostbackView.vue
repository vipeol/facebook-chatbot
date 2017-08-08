<template>
    <div>
        <h3><small>postback:</small> {{ postback.value }}</h3>

        <form @submit.prevent="save()" id="form-new-postback" v-if="showEditForm">
            <div class="input-field">
                <input id="value_to_postback" type="text" v-model="postback.value" required>
                <label for="value_to_postback" class="active">Identificação do postback</label>
            </div>
            <input type="submit" value="atualizar" class="btn">
        </form>

        <p>
            <router-link :to="{path: '/'}" class="btn waves-effect">voltar</router-link>
            <a @click.prevent="addGetStartedButton()" href="" v-if="!postback.get_started" class="btn green waves-effect waves-light">definir como botão começar</a>
            <a @click.prevent="removeGetStartedButton()" href="" v-if="postback.get_started" class="btn green waves-effect waves-light">remover botão começar</a>
            <a @click.prevent="showEditForm = !showEditForm" href="" class="btn blue waves-effect">editar</a>
            <a @click.prevent="remove()" href="" class="btn red waves-effect">remover</a>
        </p>

        <message v-for="message in postback.messages" :message-data="message" :key="message.id">

        </message>

        <div class="card light-green">
            <div class="card-content">
                <form id="formNewMessage" @submit.prevent="newMessage()">
                    <h5>Nova mensagem</h5>
                    <div class="input-filter">
                        <select class="browser-default" required v-model="dataToSave.type">
                            <option value="" disabled>Tipo da Mensagem</option>
                            <optgroup label="Mensagem">
                                <option value="text">Texto</option>
                                <option value="file">Arquivo</option>
                                <option value="audio">Audio</option>
                                <option value="image">Imagem</option>
                                <option value="video">Vídeo</option>
                            </optgroup>
                        </select>
                    </div>
                    <div id="messageField" class="input-field">
                        <input type="text" required v-model="dataToSave.message">
                        <label>Mensagem</label>
                    </div>
                    <input id="messageSaveBtn" type="submit" value="+" class="btn green">
                </form>
            </div>
        </div>

    </div>
</template>

<script>
    import swal from 'sweetalert'
    import message from './Message'

    export default {
        components: {
            'message': message
        },
        data: function () {
            return {
                showEditForm: false,
                dataToSave: {
                    type: ''
                }
            }
        },
        methods: {
            save() {
                let data = {
                    id: this.$route.params.id,
                    data: {
                        value: this.postback.value
                    }
                }
                this.$store.dispatch('updatePostback', data).then(() => {
                    swal('Salvo com sucesso','O bot já deve responder a este postback', 'success')
                    this.showEditForm = false
                })
            },
            remove () {
                swal({
                    title: "Removendo!",
                    text: "Você esta removendo este postback, ação não poderá ser desfeita!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Remover",
                    cancelButtonText: "Cancelar"
                },
                    () => {
                        this.$store.dispatch('removePostback',  this.$route.params.id).then(() => {
                            swal("Removido!", "Removido com sucesso", "success")
                            this.$router.push("/")
                        })
                    }
                )
            },
            addGetStartedButton() {
                swal({
                        title: "Botão começar?",
                        text: "Você tem certeza que quer definir este postback como ação do botão começar!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim",
                        cancelButtonText: "Não"
                    },
                    () => {
                        this.$store.dispatch('addGetStarted',  this.$route.params.id).then(() => {
                            swal("Processo concluído!", "Botão começar agora vai responder a este postback", "success")
                            this.$store.dispatch('getPostback', this.$route.params.id)
                        })
                    }
                )
            },
            removeGetStartedButton() {
                swal({
                        title: "Removendo botão começar!",
                        text: "Você esta desativando o botão começar, você poderá desfazer este botão a qualquer momento!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Remover",
                        cancelButtonText: "Cancelar"
                    },
                    () => {
                        this.$store.dispatch('removeGetStarted').then((res) => {
                            let err = res.data.error || null;
                            if (err) {
                                let message = 'Algo deu errado'
                                if (err.code === 100) {
                                    message = 'Você precisa manter o botão começar ele é necessário para a exibição do menu, remova o menu primeiro!'
                                }
                                swal ('Erro', message, 'error')
                            } else {
                                swal("Botão começar destivado!", "Para desfazer esta ação clique em [Definir como botão começar]", "success")
                            }
                        })
                    }
                )
            },
            newMessage() {
                let $ = window.jQuery;
                $('#messageSaveBtn').val('aguarde...').attr('disabled', true);

                let data = {
                    type: this.dataToSave.type,
                    message: this.dataToSave.message,
                    template: false,
                    postback_id: this.$route.params.id
                };

                let messageTypes = [
                    'text',
                    'file',
                    'audio',
                    'image',
                    'video'
                ];

                if (messageTypes.indexOf(data.type) === -1) {
                    data.template = true;
                }

                this.$store.dispatch('newMessage', data).then(() => {
                    $('#messageSaveBtn').val('+').attr('disabled', false)
                    swal('Salvo com sucesso','O bot já deve responder com a mensagem criada', 'success')
                    this.dataToSave = {type: 'text'}
                    this.$store.dispatch('getPostback', this.$route.params.id)
                })
            }
        },
        computed: {
            postback () {
                return this.$store.state.postback.postback
            }
        },
        mounted () {
            this.$store.dispatch('getPostback', this.$route.params.id)
        }
    }
</script>

<style>
    #messageField {
        background-color: rgba(255, 255, 255, 0.9);
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 2px;
    }
    #messageField input {
        margin-bottom: 0;
        border-bottom: none;
    }
    #messageField label {
        left: 10px;
    }
    #formNewMessage h5 {
        color: #fff;
    }
</style>