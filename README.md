## Começando

Este script foi desenvolvido para Monitorar o Equipamento 1FINITY™ T100 Transport Blade.

### Pré requisitos

* PHP 7.4.3
* ZABBIX 6.0.5
* COMPOSER 2
* MIB fornecidas pelo fabricante (Instalar as MIBs do diretorio ./mib no diretório /usr/share/snmp/mibs.)

```
drwxr-xr-x@ 18 v4  staff     576 16 Mai  2019 .
drwxr-xr-x@  5 v4  staff     160 13 Jun 09:43 ..
-rw-r--r--@  1 v4  staff   17876 16 Mai  2019 FSS-COMMON-LOG.mib
-rw-r--r--@  1 v4  staff   10755 13 Jun 10:03 FSS-COMMON-PM-TC.mib
-rw-r--r--@  1 v4  staff    1359 16 Mai  2019 FSS-COMMON-SMI.mib
-rw-r--r--@  1 v4  staff   44187 16 Mai  2019 FSS-COMMON-TC.mib
-rw-r--r--@  1 v4  staff   22352 16 Mai  2019 FSS-EQPT.mib
-rw-r--r--@  1 v4  staff    8039 16 Mai  2019 FSS-ETHERNET-INTERFACE.mib
-rw-r--r--@  1 v4  staff    3864 16 Mai  2019 FSS-OPTICAL-CHANNEL-INTERFACE.mib
-rw-r--r--@  1 v4  staff    3609 16 Mai  2019 FSS-OTN-OTU-INTERFACE.mib
-rw-r--r--@  1 v4  staff   27633 16 Mai  2019 FSS-PM.mib
-rw-r--r--@  1 v4  staff    4962 16 Mai  2019 FSS-SYSTEM.mib
-rw-r--r--@  1 v4  staff    9922 16 Mai  2019 FUJITSU-LLDP-MIB.mib
-rw-r--r--@  1 v4  staff    2601 16 Mai  2019 FUJITSU-PROTOCOLS-MIB.mib
-rw-r--r--@  1 v4  staff   33257 16 Mai  2019 IANAifType-MIB.mib
-rw-r--r--@  1 v4  staff   71705 16 Mai  2019 IF-MIB.mib
-rw-r--r--@  1 v4  staff  152905 16 Mai  2019 RMON-MIB.mib
drwxr-xr-x@ 20 v4  staff     640 16 Mai  2019 nobin
```

* Comentar todas as linhas no snmp.conf


### Instalação

1. Acesse o servidor zabbix via SSH, e acesse a pasta /opt.
2. Clone o repositório.
3. Acesse a pasta e rode o composer.
4. Crie um link simbólico do arquivo main.php para /usr/lib/zabbix/externalscripts/"fujitsu" (ln -s /opt/fujitsu/main.php /usr/lib/zabbix/externalscripts/fujitsu)
5. Importe o template (zbx_export_templates.yml) no zabbix.
5. Import o Host de teste (zbx_export_hosts.yml) - Altere para sua necessidade, IPS e Comunidades SNMP.

## Exemplos de uso Manual

1. php -f main.php fecDiscover (ip) (snmp_community)
2. php -f main.php llvqsfp (ip) (snmp_community)
3. php -f main.php llvcfp (ip) (snmp_community)

O Exemplo abaixo utiliza o zabbix_sender para preencher os dados, entao as regras de descoberta precisam ser rodadas antes de executa-lo.

4. php -f main.php llvcfp (ip) (snmp_community) (nome_host_no_zabbix) 