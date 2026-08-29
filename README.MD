# Sistema de Gerenciamento de Usuários

Sistema web desenvolvido para gerenciamento de usuários, com autenticação, dashboard administrativo e operações de cadastro, edição e exclusão de usuários.

O projeto possui uma interface responsiva, adaptando-se a diferentes tamanhos de tela, como computadores, tablets e dispositivos móveis.

---

## 📌 Funcionalidades

- 🔐 Autenticação de usuários
- 📊 Dashboard administrativo
- 👥 Listagem de usuários cadastrados
- ➕ Cadastro de novos usuários
- ✏️ Edição de usuários
- 🗑️ Exclusão de usuários
- 📱 Layout responsivo
- 🔒 Proteção de rotas autenticadas
- 🔗 Comunicação entre Frontend e API REST

---

## 🖥️ Demonstração

### 🔐 Login

Tela responsável pela autenticação do usuário no sistema.

<p align="center">
  <img src="/imagens-demonstrativos/telaLogin.png" alt="Tela de Login" width="750">
</p>

---

### 📊 Dashboard

Após realizar o login, o usuário é direcionado para o dashboard.

A tela apresenta informações gerais do sistema, como:

- Usuários ativos
- Sessões do dia
- Status do sistema
- Menu de navegação

#### Desktop

<p align="center">
  <img src="/imagens-demonstrativos/dashboardDesktop.png" alt="Dashboard Desktop" width="900">
</p>

#### Mobile

<p align="center">
  <img src="/imagens-demonstrativos/dashboardMobile.png" alt="Dashboard Mobile" width="400">
</p>

---

## 👥 Gerenciamento de Usuários

A seção de usuários permite visualizar e administrar os usuários cadastrados.

A tabela apresenta informações como:

- Nome
- E-mail
- Telefone
- CPF
- Ações de edição e exclusão

<p align="center">
  <img src="/imagens-demonstrativos/secaoUsuariosDesktop(create).png" alt="Gerenciamento de Usuários" width="900">
</p>

---

## ➕ Cadastro de Usuário

O sistema possui um formulário para cadastro de novos usuários.

São disponibilizados campos para:

- Nome
- E-mail
- Senha
- Confirmação de senha
- Telefone
- CPF

<p align="center">
  <img src="/imagens-demonstrativos/secaoUsuariosDesktop(create).png" alt="Cadastro de Usuário" width="900">
</p>

---

## ✏️ Edição de Usuário

Também é possível editar os dados de usuários já cadastrados.

Ao selecionar a opção de edição, os dados existentes são carregados no formulário para que possam ser alterados.

<p align="center">
  <img src="/imagens-demonstrativos/secaoUsuariosMobile(edit).png" alt="Edição de Usuário" width="400">
</p>

---

## 📱 Responsividade

A aplicação foi desenvolvida pensando em diferentes dispositivos.

No desktop, o sistema utiliza um menu lateral e organiza os conteúdos em colunas.

Em dispositivos menores, os elementos são reorganizados para facilitar a navegação e utilização.

### Desktop

- Menu lateral
- Cards organizados horizontalmente
- Formulários em duas colunas
- Tabelas adaptadas ao espaço disponível

### Mobile

- Menu de navegação compacto
- Cards organizados verticalmente
- Formulários adaptados para telas menores
- Conteúdo reorganizado para melhor utilização

---

## 🛠️ Tecnologias

### Frontend

- [Vue.js](https://vuejs.org/)
- JavaScript
- HTML5
- CSS3
- Vite
- Axios

### Backend

- [Laravel](https://laravel.com/)
- PHP
- API REST
- Laravel Sanctum

### Banco de Dados

- MySQL

### Ferramentas

- Git
- GitHub
- Composer
- NPM

---

## 🏗️ Arquitetura

O projeto é dividido em duas partes principais:

```text
┌─────────────────────┐
│      Frontend       │
│       Vue.js        │
└──────────┬──────────┘
           │
           │ HTTP / API REST
           ▼
┌─────────────────────┐
│       Backend       │
│       Laravel       │
│        PHP          │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│      MySQL          │
│     Database        │
└─────────────────────┘
