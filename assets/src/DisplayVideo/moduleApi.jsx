import { fetchConfigProps } from '../common/config';

const uri = 'api/v1/modules/';

function findById(id) {
  const config = fetchConfigProps();

  // Ensure the cookies are passed with fetch
  return fetch(`${config.baseUrl}${uri}${id}`, { credentials: 'same-origin' })
    .then(res => res.json());
}

const ModuleApi = { findById };

export default ModuleApi;
