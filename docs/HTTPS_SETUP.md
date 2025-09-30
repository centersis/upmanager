# Configuração HTTPS no Servidor Web

## 🚨 Importante

O middleware HTTPS foi desabilitado no Laravel para evitar loops infinitos de redirecionamento. Configure HTTPS diretamente no servidor web.

## 🔧 Configuração Nginx

### 1. Redirecionamento HTTP → HTTPS

```nginx
server {
    listen 80;
    server_name upmanager.com.br www.upmanager.com.br;
    return 301 https://$server_name$request_uri;
}
```

### 2. Configuração HTTPS

```nginx
server {
    listen 443 ssl http2;
    server_name upmanager.com.br www.upmanager.com.br;
    
    root /var/www/upmanager/public;
    index index.php;
    
    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=63072000" always;
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

## 🔧 Configuração Apache

### 1. Redirecionamento HTTP → HTTPS (.htaccess)

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 2. Configuração Virtual Host

```apache
<VirtualHost *:443>
    ServerName upmanager.com.br
    ServerAlias www.upmanager.com.br
    DocumentRoot /var/www/upmanager/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/upmanager/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerName upmanager.com.br
    ServerAlias www.upmanager.com.br
    Redirect permanent / https://upmanager.com.br/
</VirtualHost>
```

## 🔧 Configuração Laravel

### 1. Variáveis de Ambiente (.env)

```bash
APP_ENV=production
APP_URL=https://upmanager.com.br
SESSION_SECURE_COOKIE=true
```

### 2. AppServiceProvider

O `AppServiceProvider` já está configurado para forçar HTTPS em produção:

```php
// Forçar HTTPS em produção
if ($this->app->environment('production')) {
    URL::forceScheme('https');
}
```

## 🚀 Certificados SSL

### Let's Encrypt (Recomendado)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obter certificado
sudo certbot --nginx -d upmanager.com.br -d www.upmanager.com.br

# Renovação automática
sudo crontab -e
# Adicionar: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Certificado Comercial

1. Gerar CSR (Certificate Signing Request)
2. Solicitar certificado ao provedor
3. Instalar certificado no servidor
4. Configurar renovação automática

## ✅ Verificação

Após configurar, verifique:

1. **HTTP redireciona para HTTPS:**
   ```bash
   curl -I http://upmanager.com.br
   # Deve retornar: HTTP/1.1 301 Moved Permanently
   ```

2. **HTTPS funciona:**
   ```bash
   curl -I https://upmanager.com.br
   # Deve retornar: HTTP/1.1 200 OK
   ```

3. **Certificado válido:**
   ```bash
   openssl s_client -connect upmanager.com.br:443 -servername upmanager.com.br
   ```

## 🎯 Benefícios

- ✅ Sem loops infinitos de redirecionamento
- ✅ Melhor performance (redirecionamento no servidor)
- ✅ Headers de segurança configurados
- ✅ Certificados SSL automáticos
- ✅ Laravel funciona perfeitamente