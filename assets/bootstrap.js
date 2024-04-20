import { startStimulusApp } from '@symfony/stimulus-bridge';
import Clipboard from '@stimulus-components/clipboard'
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));
// register any custom, 3rd party controllers here
app.register('clipboard', Clipboard)