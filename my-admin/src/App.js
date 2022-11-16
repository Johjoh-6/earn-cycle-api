import * as React from "react";
import { Admin } from 'react-admin';
import { authProvider } from './authProvider';

import Login from './login';

const App = () => (
    <Admin loginPage={Login} authProvider={authProvider} requireAuth>
    ...
    </Admin>
);

export default App;