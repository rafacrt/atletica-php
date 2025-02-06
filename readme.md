# 🌐 **Sistema Atletica Linktree** 🚀

![PHP](https://img.shields.io/badge/PHP-8.0-blue?style=flat&logo=php) 
![MySQL](https://img.shields.io/badge/MySQL-8.0.40-blue?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4.5-purple?style=flat&logo=bootstrap)
![AdminLTE](https://img.shields.io/badge/AdminLTE-3.1-orange?style=flat&logo=adminlte)
![FontAwesome](https://img.shields.io/badge/Font%20Awesome-5.15.3-green?style=flat&logo=font-awesome)

## 🖥️ **Visão Geral**
Este projeto é um sistema no estilo **Linktree** desenvolvido em **PHP** e **MySQL**, permitindo que os usuários criem páginas personalizadas com seus próprios links, detalhes de perfil e badges. A interface é projetada com **Bootstrap**, estilizada usando **AdminLTE**, e inclui integrações de redes sociais e atualizações em tempo real via **AJAX**.

## 🎯 **Funcionalidades**

- 📝 **Perfis Personalizados**: Cada usuário pode criar sua própria página de perfil com uma URL exclusiva baseada no nome de usuário (`site.com/username`).
- 🎨 **Customização de Tema**: Os usuários podem escolher a cor do fundo para seus perfis públicos.
- 🔗 **Gerenciamento de Links**: Adicionar, editar e remover links de maneira simples e organizada no perfil.
- 🏆 **Badges**: Escolha até 4 badges, com ícones e títulos, para exibir no perfil público.
- 🌐 **Redes Sociais**: Adicionar dinamicamente links de redes sociais com ícones personalizados.
- 👁️ **Verificador de Força de Senha**: Feedback em tempo real sobre a força da senha com barra de progresso.
- ✉️ **Confirmação de Email**: Os usuários precisam confirmar seus emails para ativar suas contas.
- ⚙️ **Dashboard Moderno com AdminLTE**: Um dashboard limpo e moderno para gerenciar perfis, links, badges e redes sociais.

## 🔧 **Tecnologias Utilizadas**

- **PHP 8.x**: Lógica de backend.
- **MySQL**: Banco de dados para armazenar usuários, links, badges e redes sociais.
- **Bootstrap 4.5**: Para design responsivo.
- **AdminLTE 3.1**: Para o UI moderno do dashboard.
- **FontAwesome**: Para os ícones.
- **AJAX**: Para uma experiência de usuário mais fluida, sem recarregar a página inteira.
- **PHPMailer**: Para envio de emails de confirmação (opcional).

## 🚀 **Instalação & Configuração**

1. **Clone o repositório**:
   git clone https://github.com/seuusuario/atletica-linktree.git
   cd atletica-linktree
   
⚙️ Uso

🛠️ Dashboard
Faça login e acesse o dashboard, onde poderá gerenciar seu perfil, links, badges e redes sociais.

📜 Perfil Público
Os usuários podem compartilhar seu perfil único com URL (/username), onde visitantes podem ver seus links, badges e redes sociais.

📂 Estrutura de Pastas
/atletica
├── assets/                # Arquivos estáticos (CSS, JS, imagens)
├── includes/              # Conexão com o banco de dados e arquivos comuns
├── user/                  # Ações específicas dos usuários (login, registro, dashboard, etc.)
├── public/                # Páginas de perfil acessíveis publicamente
├── sql/                   # Scripts SQL do banco de dados
├── .htaccess              # Configuração do Apache para URLs amigáveis
└── README.md              # Este arquivo


🛡️ Segurança
Hashing de Senha: As senhas dos usuários são criptografadas com password_hash() para maior segurança.
Confirmação de Email: Os usuários devem confirmar seus emails antes de ativar suas contas.

🎉 Contribuindo
Sinta-se à vontade para enviar issues ou pull requests. Contribuições são muito bem-vindas! 😊

📝 Licença
MIT License

🔗 Demo | 🌟 Dê uma estrela neste repositório se achou útil!

📫 Contato
Se tiver alguma dúvida, entre em contato pelo email rafael@rajo.com.br.