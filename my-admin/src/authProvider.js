import jwtDecode from 'jwt-decode';
const entrypoint = process.env.REACT_APP_API_ENTRYPOINT;

// eslint-disable-next-line import/no-anonymous-default-export
const authProvider = {
  login: async ({ username, password }) => {
    const request = new Request(`${entrypoint}/login_check`, {
      method: 'POST',
      body: JSON.stringify({ email: username, password }),
      headers: new Headers({ 'Content-Type': 'application/json' }),
    });
    const response = await fetch(request);
    if (response.status < 200 || response.status >= 300) {
      throw new Error(response.statusText);
    }
    const { token } = await response.json();
    sessionStorage.setItem('auth-token', token);
  },
  logout: () => {
    sessionStorage.removeItem('auth-token');
    return Promise.resolve();
  },
  checkAuth: () => {
    try {
      if (
        !sessionStorage.getItem('auth-token') ||
        new Date().getTime() / 1000 >
          // @ts-ignore
          jwtDecode(sessionStorage.getItem('auth-token'))?.exp
      ) {
        return Promise.reject();
      }
      return Promise.resolve();
    } catch (e) {
      // override possible jwtDecode error
      return Promise.reject();
    }
  },
  checkError: (err) => {
    if ([401, 403].includes(err?.status || err?.response?.status)) {
      sessionStorage.removeItem('auth-token');
      return Promise.reject();
    }
    return Promise.resolve();
  },
  getPermissions: () => Promise.resolve(),
};

export default authProvider;