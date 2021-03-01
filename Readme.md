# Integration Symfony avec Vitejs

## Dev

- lancer le serveur 
```
yarn dev
```
Pour avoir les fichiers img crée un un lien symbolique dans le dossier public
```
cd public/
ln -s ../assets/ assets
```
- Installer sass sur le projet
```
npm install -D sass
```

## Prod

- Mettre le .env.local sur 0
```
VITE_DEV=0
```
- Supprimer le lien symbolique
```
rm public/assets
```

- 1 seul lien de sortie
```js 
output: {
       manualChunks: undefined
      }
```

- Fonction async
```
import 'vite/dynamic-import-polyffill
```

- Build nos assets
```
yarn build
```


