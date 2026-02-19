# Example: sudo bash create-client-dns.sh crazycms.net

if [[ $# -eq 0 ]] ; then
    echo 'Please enter domain name'
    exit 0
fi

# get domain from command argument
domain=$1

# create opendkim key dir
sudo mkdir /etc/opendkim/keys/$domain
sudo chown opendkim:opendkim /etc/opendkim/keys/$domain

# create DKIM keys
sudo opendkim-genkey -b 2048 -d $domain -D /etc/opendkim/keys/$domain -s default -v
sudo chown -R opendkim:opendkim /etc/opendkim/keys/

# Update opendkim files to add the new domain
sudo echo "default._domainkey.$domain $domain:default:/etc/opendkim/keys/$domain/default.private" >> /etc/opendkim/KeyTable
sudo echo "*@$domain default._domainkey.$domain" >> /etc/opendkim/SigningTable
sudo echo "*.$domain" >> /etc/opendkim/TrustedHosts