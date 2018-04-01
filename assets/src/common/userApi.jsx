import { fetchConfigProps } from './config';

const uri = 'api/v1/users/';

function findCurrentUser() {
  const config = fetchConfigProps();

  return fetch(`${config.baseUrl}${uri}me`, { credentials: 'same-origin' })
    .then(res => res.json());
}

const UserApi = { findCurrentUser };

export default UserApi;
