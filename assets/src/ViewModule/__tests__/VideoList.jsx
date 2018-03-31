import { render } from 'enzyme';

import VideoList from '../VideoList';

describe('<VideoList />', () => {
  let videos;

  beforeEach(() => {
    videos = [
      {
        title: 'Test title',
        shortDescription: 'hello world',
        url: 'https://test.com/test',
      },
      {
        title: 'Test title 2',
        shortDescription: 'Lorem lipsum dolor sit amed',
        url: 'https://test.com/test2',
      },
    ];
  });

  it('should render list properly', () => {
    const wrapper = render(<VideoList videos={videos} />);

    expect(wrapper).toMatchSnapshot();
  });
});
